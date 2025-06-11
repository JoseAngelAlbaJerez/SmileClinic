<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Procedure;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Event;
use App\Models\Patient;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
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


        $query = Budget::query()->select('budgets.*');
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
            $query->latest('budgets.updated_at')
                ->latest('budgets.created_at');
        }
        if ($lastDays) {
            if (is_numeric($lastDays)) {
                // Filtro en días numéricos: last 1, 7, 30, etc.
                $dateFrom = Carbon::now()->subDays((int) $lastDays)->startOfDay();
                $query->where('created_at', '>=', $dateFrom);
            } else {
                // Filtros especiales tipo 'month' o 'year'
                if ($lastDays === 'month') {
                    $dateFrom = Carbon::now()->startOfMonth();
                    $query->where('created_at', '>=', $dateFrom);
                } elseif ($lastDays === 'year') {
                    $dateFrom = Carbon::now()->startOfYear();
                    $query->where('created_at', '>=', $dateFrom);
                }
            }
        }

        $budgets = $query->orderByDesc('created_at')->with('doctor', 'patient')->paginate(10);
        return Inertia::render('Budgets/Index', [
            'budgets' => $budgets,
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
        $patient = Patient::paginate(10);
        $procedure = Procedure::paginate(10);
        return Inertia::render('Budgets/Create', [
            'patient' => $patient,
            'procedure' => $procedure,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'form.patient_id' => 'required|exists:patients,id',
            'form.type' => 'required|string',
            'form.emission_date' => 'required|date',
            'form.expiration_date' => 'nullable|date',
            'form.total' => 'required|numeric',

            'details' => 'required|array|min:1',
            'details.*.treatment' => 'required|string|max:100',
            'details.*.amount' => 'required|numeric',
            'details.*.total' => 'required|numeric',
            'details.*.discount' => 'required|integer',
            'details.*.quantity' => 'required|integer',
            'details.*.procedure_id' => 'required|integer',
        ]);

        $budgetData = $validated['form'];
        $budgetData['emission_date'] = Carbon::parse($budgetData['emission_date'] ?? now());
        $budgetData['expiration_date'] = $budgetData['expiration_date'] ? Carbon::parse($budgetData['expiration_date']) : null;
        $budgetData['doctor_id'] = Auth::id();
        $budget = Budget::create($budgetData);
        $budget->budgetdetail()->createMany($validated['details']);


        return redirect()->route('budgets.index')->with('toast', 'Presupuesto guardado correctamente');
    }


    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        $budget->load('doctor', 'patient', 'budgetdetail.procedure');
        return Inertia::render("Budgets/Show", [
            'budgets' => $budget,
        ]);
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
    public function update(Request $request, Budget $budget)
    {
        if ($request->has('active')) {
            $this->restore($budget);
            return redirect()->back()->with('toast', 'Presupuesto restaurado correctamente');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        $budget->active = 0;
        $budget->budgetdetail()->get()->each(function ($budgetDetail) use ($budget) {
            $budgetDetail->active = 0;
            $budgetDetail->save();
        });
        $budget->save();

        return redirect()->back()->with('toast', 'Presupuesto eliminado correctamente');
    }
    private function restore(Budget $budget)
    {
        $budget->active = 1;
        $budget->save();

        return redirect()->back()->with('toast', 'Presupuesto restaurado correctamente');
    }
}
