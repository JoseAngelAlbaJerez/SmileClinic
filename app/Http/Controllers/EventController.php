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


        $query = Event::query()
            ->select('events.*')
            ->join('patients', 'events.patient_id', '=', 'patients.id')
            ->join('users', 'events.doctor_id', '=', 'users.id');

        if ($showDeleted == true) {
            $query->where('events.active', 1);
        } else {
            $query->where('events.active', 0);
        }

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->whereRaw('patients.first_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('patients.last_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('patients.date_of_birth LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('CONCAT(patients.first_name, " ", COALESCE(patients.last_name, "")) LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('CONCAT(users.first_name, " ", COALESCE(users.last_name, "")) LIKE ?', ['%' . $search . '%']);
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
                // Filtro en días numéricos: last 1, 7, 30, etc.
                $dateFrom = Carbon::now()->subDays((int) $lastDays)->startOfDay();
                $query->where('events.created_at', '>=', $dateFrom);
            } else {
                // Filtros especiales tipo 'month' o 'year'
                if ($lastDays === 'month') {
                    $dateFrom = Carbon::now()->startOfMonth();
                    $query->where('events.created_at', '>=', $dateFrom);
                } elseif ($lastDays === 'year') {
                    $dateFrom = Carbon::now()->startOfYear();
                    $query->where('events.created_at', '>=', $dateFrom);
                }
            }
        }

        $events = $query->orderByDesc('events.created_at')->with('doctor', 'patient')->paginate(10);

        return Inertia::render('Events/Index', [
            'events' => $events,
            'filters' => [
                'search' => $search,
                'sortField' => $sortField,
                'sortDirection' => $sortDirection,
                'activeStates' => $activeStates,
                'lastDays' => $lastDays,
                'showdeleted' => $showDeleted,

            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = User::role('doctor')->get();
        $patients = Patient::all();
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
        $validated = $request->validate([
            'title'       => 'required|string|max:100',
            'doctor_id'   => 'required|integer',
            'patient_id'  => 'required|integer',
            'start_time'  => ['required', 'date_format:H:i', 'before:end_time'],
            'end_time'    => ['required', 'date_format:H:i', 'after:start_time'],
            'date'        => 'required|date',
        ]);

        $validated['attended'] = true;
        $validated['active'] = true;

        Event::create($validated);

        return response()->json([
            'message' => 'Cita registrada correctamente.'
        ]);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
