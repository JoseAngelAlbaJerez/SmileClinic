<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Budget;
use App\Models\Odontograph;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Patient;
use App\Models\Event;
use App\Models\Prescription;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:patient.view', only: ['index', 'show']),
            new Middleware('permission:patient.create', only: ['create', 'store']),
            new Middleware('permission:patient.update', only: ['edit', 'update']),
            new Middleware('permission:patient.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {

        $search = $request->input('search');
        $sortField = $request->input('sortField');
        $sortDirection = $request->input('sortDirection', 'asc');
        $activeStates = $request->input('activeStates', []);
        $lastDays = $request->input('lastDays', '30');
        $showDeleted = filter_var($request->input('showDeleted', 'true'), FILTER_VALIDATE_BOOLEAN);


        $query = Patient::query()->select('patients.*');
        if ($showDeleted == true) {
            $query->where('active', 1);
        } else {
            $query->where('active', 0);
        }

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->WhereRaw('first_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('last_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('date_of_birth LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('ars LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('date_of_birth LIKE ?', ['%' . $search . '%']);
            });
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('patients.updated_at')
                ->latest('patients.created_at');
        }
        if ($lastDays) {
            if (is_numeric($lastDays)) {

                $dateFrom = Carbon::now()->subDays((int) $lastDays)->startOfDay();
                $query->where('created_at', '>=', $dateFrom);
            } else {

                if ($lastDays === 'month') {
                    $dateFrom = Carbon::now()->startOfMonth();
                    $query->where('created_at', '>=', $dateFrom);
                } elseif ($lastDays === 'year') {
                    $dateFrom = Carbon::now()->startOfYear();
                    $query->where('created_at', '>=', $dateFrom);
                }
            }
        }

        $patients = $query->with('branch')->orderByDesc('created_at')->paginate(10);
        return Inertia::render('Patients/Index', [
            'patients' => $patients,
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
    public function filter(Request $request)
{
    $filters = $request->input('filters', []);

    $query = Patient::query()
        ->when(!empty($filters['name']), function ($q) use ($filters) {
            $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $filters['name'] . '%']);
        });

    $patients = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

    if ($request->wantsJson()) {
        return response()->json([
            'patients' => $patients
        ]);
    }

    // si entras normal (con Inertia, no por axios)
    return Inertia::render('Patients/Index', [
        'patients' => $patients,
        'filters'  => $filters,
    ]);
}



    public function create()
    {
        return Inertia::render("Patients/Create");
    }

    public function show(Patient $patient, Request $request)
    {

        $showDeleted = filter_var($request->input('showDeleted', 'true'), FILTER_VALIDATE_BOOLEAN);
        $search = $request->input('search');
        $query = Odontograph::with('doctor')
            ->join('users', 'odontographs.doctor_id', '=', 'users.id')
            ->where('odontographs.patient_id', $patient->id)
            ->select('odontographs.*', 'users.name as doctor_name', 'users.last_name as doctor_last_name');


        if ($showDeleted) {
            $query->where('odontographs.active', true);
        } else {
            $query->where('odontographs.active', false);
        }

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->WhereRaw('CONCAT(users.name, " ", COALESCE(users.last_name, "")) LIKE ?', ['%' . $search . '%'])
                    ->orWhere('odontographs.id', $search);
            });
        }

        $events = Event::where('patient_id', $patient->id)->with('doctor')->get();
        $budgets = Budget::where('patient_id', $patient->id)->with('doctor', 'patient', 'budgetdetail.procedure')->get();
        $bills = Bill::where('patient_id', $patient->id)->with('doctor', 'patient', 'billdetail.procedure')->get();
        $odontograph = $query->orderByDesc('created_at')->get();
        $prescription = Prescription::where('patient_id', $patient->id)->with('patient', 'doctor', 'prescriptionsDetails.drugs')->orderByDesc('created_at')->get();
        $patient->age =  Carbon::parse($patient->date_of_birth)->age;
        return Inertia::render('Patients/Show', [
            'patient' => $patient,
            'budgets' => $budgets,
            'bills' => $bills,
            'events' => $events,
            'prescription' => $prescription,
            'odontograph' => $odontograph,
            'filters' => [
                'search' => $search,
                'showDeleted' => $showDeleted,

            ],
        ]);
    }
    public function edit(Patient $patient)
    {
        return Inertia::render('Patients/Edit', [
            'patient' => $patient
        ]);
    }

    public function update(Request $request, Patient $patient)
    {
        if ($request->has('active')) {
            $this->restore($patient);
            return redirect()->back()->with('toast', 'Paciente restaurado correctamente.');
        }
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'DNI' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'ars' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'address' => 'nullable|string|max:255',
            'motive' => 'nullable|string|max:255',
            'complications' => 'boolean',
            'complications_detail' => 'nullable|string',
            'drugs' => 'boolean',
            'drugs_detail' => 'nullable|string',
            'alergies' => 'boolean',
            'alergies_detail' => 'nullable|string',
        ]);

        $patient->update($data);

        return redirect()->back()->with('toast', 'Paciente actualizado correctamente.');
    }
    private function restore(Patient $patient)
    {
        $patient->active = 1;
        $patient->save();

        return redirect()->back()->with('toast', 'Paciente restaurado correctamente.');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name'           => 'required|string|max:100',
                'last_name'            => 'required|string|max:100',
                'ars'            => 'nullable|string|max:100',
                'DNI'                  => 'nullable|string|max:20|unique:patients,DNI',
                'phone_number'         => 'nullable|string|max:20',
                'date_of_birth'        => 'nullable|date|before:today',
                'complications'        => 'required|boolean',
                'complications_detail' => 'nullable|string',
                'drugs'                => 'required|boolean',
                'drugs_detail'         => 'nullable|string',
                'alergies'             => 'required|boolean',
                'alergies_detail'      => 'nullable|string',
                'address'              => 'nullable|string|max:255',
                'motive'               => 'nullable|string|max:255',
            ]);

            $validated['active'] = true;
            $validated['branch_id'] = Auth::user()->branch_id;
            $patient = Patient::create($validated);

            return redirect()->route('patients.index')->with('toast', 'Paciente registrado correctamente.');
        } catch (ValidationException $e) {
            return back()
                ->with('toast', 'Ocurrió un error. ' . $e->getMessage())
                ->with('toastStyle', 'danger');
        }
    }
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->active = false;
        $patient->save();

        return redirect()->back()->with('toast', 'Paciente desactivado correctamente.');
    }
}
