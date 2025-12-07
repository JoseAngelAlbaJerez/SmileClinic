<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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

        $search        = trim($request->input('search'));
        $sortField     = $request->input('sortField', null);
        $sortDirection = $request->input('sortDirection', 'asc');
        $lastDays      = $request->input('lastDays', '1');
        $patient_id    = $request->input('patient_id', null);
        $showDeleted   = filter_var($request->input('showDeleted', false), FILTER_VALIDATE_BOOLEAN);



        $query = Event::query()
            ->with(['patient', 'doctor', 'branch'])
            ->select('events.*')
            ->join('users as patients', 'events.patient_id', '=', 'patients.id')
            ->leftJoin('users as doctors', 'events.doctor_id', '=', 'doctors.id');



        if ($showDeleted) {
            $query->where('events.active', 0);
        } else {
            $query->where('events.active', 1);
        }



        if ($patient_id) {
            $query->where('events.patient_id', $patient_id);
            $lastDays = 'year';
        }



        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('events.title', 'like', "%{$search}%")
                    ->orWhere('events.description', 'like', "%{$search}%")
                    ->orWhere('patients.first_name', 'like', "%{$search}%")
                    ->orWhere('patients.last_name', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(patients.first_name, ' ', patients.last_name) LIKE ?", ["%{$search}%"])
                    ->orWhere('doctors.first_name', 'like', "%{$search}%");
            });
        }



        if ($lastDays) {

            if (is_numeric($lastDays)) {
                $query->where('events.date', '>=', now()->subDays((int)$lastDays));
            } elseif ($lastDays === 'month') {
                $query->where('events.date', '>=', now()->startOfMonth());
            } elseif ($lastDays === 'year') {
                $query->where('events.date', '>=', now()->startOfYear());
            }
        }



        if ($sortField) {
            $query->orderBy("events.$sortField", $sortDirection);
        } else {
            $query->orderBy('events.date', 'asc');
        }


        $events = $query->get()
            ->map(function ($event) {
                return [
                    'id'        => $event->id,
                    'title'     => $event->title ?? 'Sin título',
                    'start'     => $event->date . 'T' . $event->starttime,
                    'end'       => $event->date . 'T' . $event->endtime,
                    'patient'   => $event->patient,
                    'branch'    => $event->branch,
                    'doctor'    => $event->doctor,
                    'attended'  => $event->attended,
                    'active'    => $event->active,
                ];
            });

        return Inertia::render('Events/Index', [
            'events' => $events->toArray(),
            'filters' => [
                'search'        => $search,
                'patient_id'    => $patient_id,
                'sortField'     => $sortField,
                'sortDirection' => $sortDirection,
                'lastDays'      => $lastDays,
                'showDeleted'   => $showDeleted,
            ],
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $patient_id = $request->input('patient_id');
        if ($patient_id) {
            $patient = User::where('id', $patient_id)
                ->with([
                    'CXC',
                    'bill.billdetail',
                    'budget' => function ($q) {
                        $q->where('active', 1)
                            ->with([
                                'budgetdetail' => function ($d) {
                                    $d->where('active', 1)
                                        ->with('procedure');
                                },
                                'patient'
                            ]);
                    }
                ])
                ->first();
        } else {
            $patient = null;
        }
        $doctors = User::role('doctor')->with('roles', 'Event')->paginate(10);
        $patients = User::role('patient')->with('roles')->paginate(10);
        $events = Event::all()->load('doctor', 'patient');
        return Inertia::render("Events/Create", [
            'doctors' => $doctors,
            'patients' => $patients,
            'patient' => $patient,
            'events' => $events,
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

        //put constraint to take into consideration branches
        $constraint = Event::where('doctor_id', $validated['doctor_id'])
            ->where('date', $validated['date'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('starttime', [$request->starttime, $request->endtime])
                    ->orWhereBetween('endtime', [$request->starttime, $request->endtime])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('starttime', '<=', $request->starttime)
                            ->where('endtime', '>=', $request->endtime);
                    });
            })
            ->exists();

        if ($constraint) {
            return back()->withInput()->with('toast', 'Ya existe una cita en ese horario para ese doctor.')
                ->with('toastStyle', 'danger');
        }
        $validated['attended'] = false;
        $validated['active'] = true;
        $validated['branch_id'] = Auth::user()->active_branch_id;
        $event =  Event::create($validated);
        // try {
        //     $googleEvent = GoogleCalendarEvent::create([
        //         'name' => $request->input('title'),
        //         'startDateTime' => $startDateTime,
        //         'endDateTime' => $endDateTime,
        //     ]);

        //     $event->google_event_id = $googleEvent->id;
        //     $event->save();
        // } catch (\Exception $e) {

        //     $event->delete();
        //     Log::error($e);
        //     return back()
        //         ->with('toast', 'Ocurrió un error. ' . $e->getMessage())
        //         ->with('toastStyle', 'danger');
        // }


        return redirect()->route('events.index')->with('toast', 'Cita registrada correctamente');
    }
    public function update(Request $request, Event $event)
    {
        if ($request->has('active')) {
            $this->restore($event);
            return redirect()->back()->with('toast', 'Cita restaurada correctamente');
        } else if ($request->has('attended')) {
            $this->attend($event, $request->attended);
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
        $event->attended_at = now();
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
        $starttime = $event->starttime = Carbon::parse($event->starttime)->format('H:i');
        $endtime = $event->endtime = Carbon::parse($event->endtime)->format('H:i');
        $doctors = User::role('doctor')->with('roles')->paginate(10);
        $patients = User::role('patient')->with('roles')->paginate(10);
        $event->timeRange = [$starttime, $endtime];
        $events = Event::all()->load('doctor', 'patient');

        return Inertia::render('Events/Edit', [
            'event' => $event,
            'doctors' => $doctors,
            'patients' => $patients,
            'events' => $events,

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
            ->with('toast', 'Cita cancelada correctamente');
    }
}
