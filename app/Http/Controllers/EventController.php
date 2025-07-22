<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;
use Spatie\GoogleCalendar\Event as GoogleCalendarEvent;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortField = $request->input('sortField');
        $sortDirection = $request->input('sortDirection', 'asc');
        $activeStates = $request->input('activeStates', []);
        $lastDays = $request->input('lastDays', '1');
        $showDeleted = filter_var($request->input('showDeleted', 'true'), FILTER_VALIDATE_BOOLEAN);
        $patient_id = $request->input('patient_id');


        $query = Event::query()
            ->select('events.*')
            ->join('patients', 'events.patient_id', '=', 'patients.id')
            ->join('users', 'events.doctor_id', '=', 'users.id');

        if ($showDeleted == true) {
            $query->where('events.active', 1);

        } else {
            $query->where('events.active', 0);

        }

        if ($patient_id) {
            $patient = Patient::find($patient_id);

            $eventDates = $patient->event()->pluck('created_at');

            if ($eventDates->isNotEmpty()) {
                $query->where('patient_id', $patient_id)
                    ->whereIn('events.created_at', $eventDates)
                    ->latest();
                $lastDays = 'year';
            }
        }

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->whereRaw('patients.first_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('events.title LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('patients.last_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('patients.date_of_birth LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('CONCAT(patients.first_name, " ", COALESCE(patients.last_name, "")) LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('CONCAT(users.name, " ", COALESCE(users.last_name, "")) LIKE ?', ['%' . $search . '%']);
            });
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('events.updated_at')
                ->latest('events.created_at');
        }
        if ($lastDays) {
            if (is_numeric($lastDays)) {

                $dateFrom = Carbon::now()->subDays((int) $lastDays)->startOfDay();
                $query->where('events.created_at', '>=', $dateFrom);
            } else {

                if ($lastDays === 'month') {
                    $dateFrom = Carbon::now()->startOfMonth();
                    $query->where('events.created_at', '>=', $dateFrom);
                } elseif ($lastDays === 'year') {
                    $dateFrom = Carbon::now()->startOfYear();
                    $query->where('events.created_at', '>=', $dateFrom);
                }
            }
        }

        $events = $query->orderByDesc('events.created_at')
            ->with('doctor', 'patient')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title ?? 'Sin título',
                    'start' => $event->date . 'T' . $event->starttime,
                    'end' => $event->date . 'T' . $event->endtime,
                    'patient' => $event->patient,
                    'doctor' => $event->doctor,
                    'attended' => $event->attended,
                    'active' => $event->active,
                ];
            });
        return Inertia::render('Events/Index', [
            'events' =>  $events->toArray(),
            'filters' => [
                'search' => $search,
                'patient_id' => $patient_id,
                'sortField' => $sortField,
                'sortDirection' => $sortDirection,
                'activeStates' => $activeStates,
                'lastDays' => $lastDays,
                'showDeleted' => $showDeleted,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = User::role('doctor')->with('roles')->paginate(10);
        $patients = Patient::paginate(10);
        return Inertia::render("Events/Create", [
            'doctors' => $doctors,
            'patients' => $patients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $timezone = 'America/Santo_Domingo';

        $validated = $request->validate([
            'title'       => 'required|string|max:100',
            'doctor_id'   => 'required|integer',
            'patient_id'  => 'required|integer',
            'starttime'  => ['required', 'date_format:H:i', 'before:endtime'],
            'endtime'    => ['required', 'date_format:H:i', 'after:starttime'],
            'date'        => 'required|date',
        ]);
        $startDateTime = Carbon::parse($request->input('date') . ' ' . $request->input('starttime'))->setTimezone($timezone);

        $endDateTime = Carbon::parse($request->input('date') . ' ' . $request->input('endtime'))->setTimezone($timezone);

        $validated['attended'] = false;
        $validated['active'] = true;

        $event =  Event::create($validated);
        try {
            $googleEvent = GoogleCalendarEvent::create([
                'name' => $request->input('title'),
                'startDateTime' => $startDateTime,
                'endDateTime' => $endDateTime,
            ]);

            $event->google_event_id = $googleEvent->id;
            $event->save();
        } catch (\Exception $e) {

            $event->delete();

            return back()
                ->with('toast', 'Ocurrió un error. ' . $e->getMessage())
                ->with('toastStyle', 'danger');
        }


        return redirect()->route('events.index')->with('toast', 'Cita registrada correctamente');
    }
    public function update(Request $request, Event $event)
    {
        if ($request->has('active')) {
            $this->restore($event);
            return redirect()->back()->with('toast', 'Cita restaurada correctamente');
        } else if ($request->has('attended')) {
            $this->attend($event, $request->attended);
            return redirect()->back()->with('toast', 'Cita atendida correctamente');
        }
        $data = $request->validate([
            'title'       => 'required|string|max:100',
            'doctor_id'   => 'required|integer',
            'patient_id'  => 'required|integer',
            'starttime'  => ['required', 'date_format:H:i', 'before:endtime'],
            'endtime'    => ['required', 'date_format:H:i', 'after:starttime'],
            'date'        => 'required|date',
        ]);

        $event->update($data);

        return redirect()->route('events.index')->with('toast', 'Cita actualizada correctamente');
    }
    private function restore(Event $event)
    {
        $event->active = 1;
        $event->save();

        return redirect()->back()->with('toast', 'Cita restaurada correctamente');
    }
    private function attend(Event $event, $attended)
    {
        $event->attended = $attended;
        $event->save();
        if ($event->attended == 1) {
            return redirect()->back()->with('toast', 'Cita atendida correctamente');
        } else {
            return redirect()->back()->with('toast', 'Cita desatendida correctamente');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $event->load(['patient', 'doctor']);
        $event->starttime = Carbon::parse($event->starttime)->format('H:i');
        $event->endtime = Carbon::parse($event->endtime)->format('H:i');
        $doctors = User::role('doctor')->paginate(10);
        $patients = Patient::paginate(10);
        return Inertia::render('Events/Edit', [
            'event' => $event,
            'doctors' => $doctors,
            'patients' => $patients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {

        $event->active = 0;
        $event->save();
        return redirect()->back()
            ->with('toast', 'Cita desactivada correctamente');
    }
}
