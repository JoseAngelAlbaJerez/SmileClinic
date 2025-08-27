<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\CXC;
use App\Models\CXCDetail;
use App\Models\Procedure;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Event;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('selector')) {
            $budgets = Budget::with('patient')
                ->when(
                    $request->search,
                    fn($q, $search) =>
                    $q->whereHas(
                        'patient',
                        fn($p) =>
                        $p->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%'])
                    )
                )->where('active','=',1)
                ->paginate(10);

            return inertia('Budgets/BudgetSelector', [
                'budgets' => $budgets
            ]);
        }

        $search = $request->input('search');
        $sortField = $request->input('sortField');
        $sortDirection = $request->input('sortDirection', 'asc');
        $activeStates = $request->input('activeStates', []);
        $lastDays = $request->input('lastDays', '30');
        $showDeleted = filter_var($request->input('showDeleted', 'true'), FILTER_VALIDATE_BOOLEAN);
        $patient_id = $request->input('patient_id');

        $query = Budget::query()->select('budgets.*')
            ->join('patients', 'budgets.patient_id', '=', 'patients.id');

        $query->where('budgets.active', $showDeleted ? 1 : 0);

        if ($patient_id) {
            $patient = Patient::find($patient_id);
            $createdAtDates = $patient->budget()->pluck('created_at');

            if ($createdAtDates->isNotEmpty()) {
                $query->where('budgets.patient_id', $patient_id)
                    ->whereIn('budgets.created_at', $createdAtDates)
                    ->latest();
            }
        }



        if ($search) {
            $query->whereHas('patient', function (Builder $q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('date_of_birth', 'LIKE', "%{$search}%")
                    ->orWhere('ars', 'LIKE', "%{$search}%");
            });
        }


        if ($lastDays) {
            if (is_numeric($lastDays)) {
                $dateFrom = Carbon::now()->subDays((int) $lastDays)->startOfDay();
                $query->where('budgets.created_at', '>=', $dateFrom);
            } else {
                if ($lastDays === 'month') {
                    $dateFrom = Carbon::now()->startOfMonth();
                    $query->where('budgets.created_at', '>=', $dateFrom);
                } elseif ($lastDays === 'year') {
                    $dateFrom = Carbon::now()->startOfYear();
                    $query->where('budgets.created_at', '>=', $dateFrom);
                }
            }
        }
        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('updated_at')->latest('created_at');
        }

         $budgets = $query->with('patient','branch')->paginate(10);
        return Inertia::render('Budgets/Index', [
            'budgets' => $budgets,
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
            'form.currency' => 'required|string',
            'form.emission_date' => 'required|date',
            'form.expiration_date' => 'nullable|date',
            'form.total' => 'required|numeric',
            'form.amount_of_payments' => 'nullable|numeric',

            'details' => 'required|array|min:1',
            'details.*.treatment' => 'required|string|max:100',
            'details.*.amount' => 'required|numeric',
            'details.*.total' => 'required|numeric',
            'details.*.discount' => 'required|integer',
            'details.*.quantity' => 'required|integer',
            'details.*.procedure_id' => 'required|integer',
            'details.*.amount_of_payments' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('form.type') === 'Crédito' && (!is_numeric($value) || $value <= 1)) {
                        $fail("La cantidad de pagos debe ser mayor que 1 cuando el tipo es Crédito.");
                    }
                }
            ],
            'details.*.initial' => 'nullable|integer',
        ]);

        $budgetData = $validated['form'];
        $budgetData['branch_id'] = Auth::user()->branch_id;
        $budgetData['emission_date'] = Carbon::parse($budgetData['emission_date'] ?? now());
        $budgetData['expiration_date'] = $budgetData['expiration_date'] ? Carbon::parse($budgetData['expiration_date']) : null;
        $budgetData['doctor_id'] = Auth::id();
        $budget = Budget::create($budgetData);
         $details = collect($validated['details'])->map(function ($detail) use ($budget) {
            $detail['branch_id'] = $budget->branch_id;
            return $detail;
        })->toArray();
        $budgetDetails  = $budget->budgetdetail()->createMany($details);
        // if ($budget->type == "Crédito") {
        //     $patient = $budget->patient;

        //     if (!$patient->cxc) {
        //         $CXC = CXC::create([
        //             'balance' => $budget->total,
        //             'patient_id' => $budget->patient_id,
        //             'doctor_id' => $budget->doctor_id,
        //         ]);
        //     } else {
        //         $CXC = $patient->cxc;
        //         $CXC->balance += $budget->total;
        //         $CXC->save();
        //     }
        //     foreach ($budgetDetails as $detail) {

        //         $remaining_amount = $detail->total / $detail->amount_of_payments;
        //         for ($i = 0; $i < $detail->amount_of_payments; $i++) {
        //             Payment::create([
        //                 'budget_detail_id' => $detail->id,
        //                 'c_x_c_id' => $CXC->id,
        //                 'amount_paid' => 0,
        //                 'expiration_date' => $budgetData['expiration_date']->addMonth(),
        //                 'remaining_amount' => $remaining_amount,
        //                 'total' => $detail->total,
        //             ]);
        //         }
        //     }
        //     $budget->c_x_c_id = $CXC->id;
        //     $budget->save();
        // }


        $budget->load(['budgetdetail', 'doctor', 'patient', 'CXC']);



        return response()->json([
            'budget_id' => $budget->id,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        $budget->load('doctor', 'patient', 'budgetdetail.procedure', 'CXC', 'budgetdetail.payment');
        return Inertia::render("Budgets/Show", [
            'budgets' => $budget,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        $patient = Patient::paginate(10);
        $procedure = Procedure::paginate(10);
        $budget->load('doctor', 'patient', 'budgetdetail.procedure', 'CXC', 'budgetdetail.payment');
        return Inertia::render('Budgets/Edit', [
            'patient' => $patient,
            'budget' => $budget,
            'procedure' => $procedure,
        ]);
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
        DB::transaction(function () use ($budget) {
            $budget->active = 0;
            $CXC = $budget->CXC;

            $budget->budgetdetail()->get()->each(function ($budgetDetail) use ($budget, $CXC) {
                $budgetDetail->active = 0;
                $budgetDetail->save();

                if ($budget->type == "Crédito" && $CXC) {
                    $CXC->balance -= $budgetDetail->total;
                    $CXC->save();

                    $budgetDetail->Payment()->get()->each(function ($payment) {
                        $payment->active = 0;
                        $payment->save();
                    });
                }
            });

            $budget->save();
        });


        return redirect()->back()->with('toast', 'Presupuesto eliminado correctamente');
    }
    private function restore(Budget $budget)
    {
        DB::transaction(function () use ($budget) {
            $budget->active = 1;
            $CXC = $budget->CXC;

            $budget->budgetdetail()->get()->each(function ($budgetDetail) use ($budget, $CXC) {
                $budgetDetail->active = 1;
                $budgetDetail->save();

                if ($budget->type == "Crédito" && $CXC) {
                    $CXC->balance += $budgetDetail->total;
                    $CXC->save();

                    $budgetDetail->Payment()->get()->each(function ($payment) {
                        $payment->active = 1;
                        $payment->save();
                    });
                }
            });

            $budget->save();
        });


        return redirect()->back()->with('toast', 'Presupuesto restaurado correctamente');
    }
}
