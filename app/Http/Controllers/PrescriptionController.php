<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Drug;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrescriptionController extends Controller
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
        $lastDays = $request->input('lastDays', '30');
        $showDeleted = filter_var($request->input('showDeleted', 'true'), FILTER_VALIDATE_BOOLEAN);
        $patient_id = $request->input('patient_id');

        $query = Prescription::query()
            ->select('prescriptions.*')
            ->join('users', 'prescriptions.patient_id', '=', 'users.id');

        if ($showDeleted == true) {
            $query->where('prescriptions.active', 1);
        } else {
            $query->where('prescriptions.active', 0);
        }


        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->whereRaw('users.first_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('users.motive LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('users.last_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('users.date_of_birth LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('CONCAT(users.first_name, " ", COALESCE(users.last_name, "")) LIKE ?', ['%' . $search . '%']);
            });
        }
        if ($patient_id) {
            $patient = User::find($patient_id);

            $prescriptionDates = $patient->prescriptions()->pluck('created_at');

            if ($prescriptionDates->isNotEmpty()) {
                $query->where('patient_id', $patient_id)
                    ->whereIn('prescriptions.created_at', $prescriptionDates)
                    ->latest();
                $lastDays = 'year';
            }
        }


        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('prescriptions.updated_at')
                ->latest('prescriptions.created_at');
        }
        if ($lastDays) {
            if (is_numeric($lastDays)) {

                $dateFrom = Carbon::now()->subDays((int) $lastDays)->startOfDay();
                $query->where('prescriptions.created_at', '>=', $dateFrom);
            } else {

                if ($lastDays === 'month') {
                    $dateFrom = Carbon::now()->startOfMonth();
                    $query->where('prescriptions.created_at', '>=', $dateFrom);
                } elseif ($lastDays === 'year') {
                    $dateFrom = Carbon::now()->startOfYear();
                    $query->where('prescriptions.created_at', '>=', $dateFrom);
                }
            }
        }

        $prescriptions = $query->orderByDesc('prescriptions.created_at')->with('doctor', 'patient', 'prescriptionsDetails.drugs', 'budget', 'branch')->paginate(10);

        return Inertia::render('Prescription/Index', [
            'prescriptions' => $prescriptions,
            'filters' => [
                'search' => $search,
                'patient_id' => $patient_id,
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
        $drugs = Drug::orderByDesc('created_at')->paginate(10);
        $patient = User::role('patient')->orderByDesc('created_at')->paginate(10);
        return Inertia::render('Prescription/Create', [
            'drugs' => $drugs,
            'patient' => $patient,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'details' => 'required|array|min:1',
            'details.*.description' => 'required|string',
            'details.*.drug_id' => 'required|exists:drugs,id',
        ]);

        DB::beginTransaction();

        try {
            $prescription = Prescription::create([
                'patient_id' => $validated['patient_id'],
                'branch_id' => Auth::user()->active_branch_id,
                'doctor_id' => Auth::id(),
                'active' => true,
            ]);

            foreach ($validated['details'] as $detail) {
                $prescriptionDetail = $prescription->prescriptionsDetails()->create([
                    'description' => $detail['description'],
                    'drug_id' => $detail['drug_id'],
                    'branch_id' => Auth::user()->active_branch_id,
                    'active' => true,
                ]);
                $prescriptionDetail->save();
            }
            DB::commit();

            return redirect()->route('prescriptions.index')->with('toast', 'Receta creada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('toast', 'Ocurrió un error. ' . $e->getMessage())
                ->with('toastStyle', 'danger');
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
    public function edit(string $id)
    {
        $drugs = Drug::orderByDesc('created_at')->paginate(10);
        $patient = Patient::orderByDesc('created_at')->paginate(10);
        $prescription = Prescription::with(['patient', 'doctor', 'budget', 'prescriptionsDetails.drugs'])
            ->findOrFail($id);
        return Inertia::render('Prescription/Edit', [
            'drugs' => $drugs,
            'patient' => $patient,
            'prescription' => $prescription
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->has('active')) {
            $this->restore($id);
        }
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'details' => 'required|array|min:1',
            'details.*.description' => 'required|string',
            'details.*.drug_id' => 'required|exists:drugs,id',
        ]);

        DB::beginTransaction();

        try {
            $prescription = Prescription::findOrFail($id);

            $prescription->update([
                'patient_id' => $validated['patient_id'],
                'branch_id' => Auth::user()->active_branch_id,
                'doctor_id' => Auth::id(),
            ]);

            $prescription->prescriptionsDetails()->delete();

            foreach ($validated['details'] as $detail) {
                $prescription->prescriptionsDetails()->create([
                    'description' => $detail['description'],
                    'drug_id' => $detail['drug_id'],
                    'branch_id' => Auth::user()->active_branch_id,
                    'active' => true,
                ]);
            }

            DB::commit();

            return redirect()->route('prescriptions.index')
                ->with('toast', 'Receta actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('toast', 'Ocurrió un error. ' . $e->getMessage())
                ->with('toastStyle', 'danger');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->update(['active' => 0]);
        $prescription->prescriptionsDetails()->update(['active' => 0]);
        return redirect()->route('prescriptions.index')
            ->with('toast', 'Receta eliminada correctamente.')
            ->with('toastStyle', 'success');
    }
    private function restore(string $id){
        $prescription = Prescription::findOrFail($id);
        $prescription->update(['active' => 1]);
        $prescription->prescriptionsDetails()->update(['active' => 1]);
        return redirect()->route('prescriptions.index')
            ->with('toast', 'Receta restaurada correctamente.')
            ->with('toastStyle', 'success');
    }
}
