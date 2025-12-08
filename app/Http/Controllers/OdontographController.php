<?php

namespace App\Http\Controllers;

use App\Models\Odontograph;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Database\Eloquent\Builder;

class OdontographController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;
    public static function middleware()
    {
        return [
            new Middleware('permission:odontograph.view', only: ['index', 'show']),
            new Middleware('permission:odontograph.create', only: ['create', 'store']),
            new Middleware('permission:odontograph.update', only: ['edit', 'update']),
            new Middleware('permission:odontograph.delete', only: ['destroy']),
        ];
    }
    public function index(Request $request)
    {
        $this->authorize('view', Odontograph::class);
        $search = $request->input('search');
        $sortField = $request->input('sortField');
        $sortDirection = $request->input('sortDirection', 'asc');
        $activeStates = $request->input('activeStates', []);
        $lastDays = $request->input('lastDays', '30');
        $showDeleted = filter_var($request->input('showDeleted', 'true'), FILTER_VALIDATE_BOOLEAN);


        $query = Odontograph::query()
            ->select(
                'odontographs.*',
                'patient.first_name as patient_first_name',
                'patient.last_name as patient_last_name',
                'doctor.first_name as doctor_first_name',
                'doctor.last_name as doctor_last_name'
            )
            ->join('users as patient', 'odontographs.patient_id', '=', 'patient.id')
            ->join('users as doctor', 'odontographs.doctor_id', '=', 'doctor.id');

        $query->where('odontographs.active', $showDeleted ? 1 : 0);


        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('patient.first_name', 'LIKE', "%{$search}%")
                    ->orWhere('patient.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('doctor.first_name', 'LIKE', "%{$search}%")
                    ->orWhere('doctor.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('patient.date_of_birth', 'LIKE', "%{$search}%");
            });
        }


        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('odontographs.updated_at')
                ->latest('odontographs.created_at');
        }

        if ($lastDays) {
            if (is_numeric($lastDays)) {

                $dateFrom = Carbon::now()->subDays((int) $lastDays)->startOfDay();
                $query->where('odontographs.created_at', '>=', $dateFrom);
            } else {

                if ($lastDays === 'month') {
                    $dateFrom = Carbon::now()->startOfMonth();
                    $query->where('odontographs.created_at', '>=', $dateFrom);
                } elseif ($lastDays === 'year') {
                    $dateFrom = Carbon::now()->startOfYear();
                    $query->where('odontographs.created_at', '>=', $dateFrom);
                }
            }
        }

        $odontographs = $query->orderByDesc('odontographs.created_at')->with('patient', 'doctor')->paginate(10);

        return Inertia::render('Odontograph/Index', [
            'odontographs' => $odontographs,
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
    public function create(Request $request)
    {
        $this->authorize('create', Odontograph::class);

        $patient_id = $request->input('patient_id');

        if ($patient_id) {

            $patient = User::where('id', $patient_id)->first();
        } else {
            $patient = null;
        }
        $patients = User::role('patient')->paginate(10);

        return Inertia::render('Odontograph/Create', [
            'patients' => $patients,
            'patient' => $patient,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Odontograph::class);
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'data' => 'required|array',
        ]);
        $validated['branch_id'] = Auth::user()->active_branch_id;
        $validated['doctor_id'] = Auth::id();
        $validated['active'] = true;
        $odontogram = Odontograph::create($validated);

        return redirect()
            ->route('patients.show', $validated['patient_id'])
            ->with('toast', 'Odontograma guardado correctamente.');
    }



    public function show(Odontograph $odontograph)
    {
        $this->authorize('view', Odontograph::class);
        $odontograph->load(['patient', 'doctor', 'branch']);
        return Inertia::render('Odontograph/Show', [
            'odontograph' => $odontograph,
        ]);
    }
    public function edit(Odontograph $odontograph)
    {
        $this->authorize('update', Odontograph::class);
        $odontograph->load(['patient', 'doctor']);
        $patients = User::role('patient')->paginate(10);

        return Inertia::render('Odontograph/Edit', [
            'patients' => $patients,
            'odontographs' => $odontograph
        ]);
    }
    public function update(Request $request, Odontograph $odontograph)
    {
        $this->authorize('update', Odontograph::class);

        if ($request->has('active')) {
            $this->restore($odontograph);
            return redirect()->back()->with('toast', 'Odontograma restaurado correctamente');
        }
        $data = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'data' => 'required|array',
        ]);

        $odontograph->update($data);

        return redirect()->route('patients.show', $odontograph->patient()->first())->with('toast', 'Odontograma actualizado correctamente.');
    }
    private function restore(Odontograph $odontograph)
    {
        $this->authorize('restore', Odontograph::class);

        $odontograph->active = 1;
        $odontograph->save();

        return redirect()->back()->with('toast', 'Odontograma restaurado correctamente');
    }

    public function destroy(Odontograph $odontograph)
    {
        $this->authorize('delete', Odontograph::class);

        $odontograph->active = 0;
        $odontograph->save();

        return redirect()->back()->with('toast', 'Odontograma desactivado correctamente');
    }
}
