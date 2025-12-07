<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\CXC;
use App\Models\Expenses;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\PendingExpense;
use App\Models\Procedure;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;
    public static function middleware()
    {
        return [
            new Middleware('permission:bill.view', only: ['index', 'show']),
            new Middleware('permission:bill.create', only: ['create', 'store']),
            new Middleware('permission:bill.update', only: ['edit', 'update']),
            new Middleware('permission:bill.delete', only: ['destroy']),
        ];
    }
    public function index(Request $request)
    {
        $this->authorize('view', Bill::class);
        $search = $request->input('search');
        $sortField = $request->input('sortField');
        $sortDirection = $request->input('sortDirection', 'asc');
        $activeStates = $request->input('activeStates', []);
        $lastDays = $request->input('lastDays', '30');
        $showDeleted = filter_var($request->input('showDeleted', 'true'), FILTER_VALIDATE_BOOLEAN);
        $patient_id = $request->input('patient_id');

        $query = Bill::query()->select('bills.*')
            ->join('users', 'patient_id', '=', 'users.id');
        if ($showDeleted == true) {
            $query->where('bills.active', 1);
        } else {
            $query->where('bills.active', 0);
        }

        if ($patient_id) {
            $patient = User::find($patient_id);
            $createdAtDates = $patient->bill()->pluck('created_at');

            if ($createdAtDates->isNotEmpty()) {
                $query->where('patient_id', $patient_id)
                    ->whereIn('bills.created_at', $createdAtDates)
                    ->latest();
            }
        }

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->WhereRaw('users.first_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('users.last_name LIKE ?', ['%' . $search . '%']);
            });
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('bills.updated_at')
                ->latest('bills.created_at');
        }
        if ($lastDays) {
            if (is_numeric($lastDays)) {
                $dateFrom = Carbon::now()->subDays((int) $lastDays)->startOfDay();
                $query->where('bills.created_at', '>=', $dateFrom);
            } else {
                if ($lastDays === 'month') {
                    $dateFrom = Carbon::now()->startOfMonth();
                    $query->where('bills.created_at', '>=', $dateFrom);
                } elseif ($lastDays === 'year') {
                    $dateFrom = Carbon::now()->startOfYear();
                    $query->where('bills.created_at', '>=', $dateFrom);
                }
            }
        }

        $bills = $query->orderByDesc('created_at')->with('doctor', 'patient', 'branch', 'billdetail.procedure')->paginate(10);
        return Inertia::render('Bills/Index', [
            'bills' => $bills,
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
    public function create(Request $request)
    {
        $this->authorize('create', Bill::class);
        $patient_id = $request->input('patient_id');
        if ($patient_id) {
            $patients = User::where('id', $patient_id)
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
            $patients = null;
        }

        $patient = User::role('patient')->with([
            'CXC',
            'bill.billdetail',
            'budget' => function ($q) {
                $q->where('active', 1)
                    ->with(['budgetdetail' => function ($d) {
                        $d->where('active', 1)
                            ->with('procedure');
                    }, 'patient']);
            },
        ])->paginate(10);


        $procedure = Procedure::paginate(10);
        $doctors = User::role('doctor')->with('roles')->paginate(10);

        return Inertia::render('Bills/Create', [
            'patient' => $patient,
            'patients' => $patients,
            'doctors' => $doctors,
            'procedure' => $procedure,
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'form.patient_id' => 'required|exists:users,id',
            'form.type' => 'required|string',
            'form.emission_date' => 'required|date',
            'form.expiration_date' => 'nullable|date',
            'form.total' => 'required|numeric',
            'form.amount_of_payments' => 'nullable|numeric',
            'form.doctor_id' => 'required|exists:users,id',
            'form.currency' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.treatment' => 'required|string|max:100',
            'details.*.amount' => 'required|numeric',
            'details.*.amount_doctor' => 'required|numeric',
            'details.*.materials_amount' => 'required|numeric',
            'details.*.total' => 'required|numeric',
            'details.*.discount' => 'required|integer',
            'details.*.quantity' => 'required|integer',
            'details.*.procedure_id' => 'required|integer',
            'details.*.initial' => 'nullable|integer',
            'details.*.amount_of_payments' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('form.type') === 'Crédito' && (!is_numeric($value) || $value <= 1)) {
                        $fail("La cantidad de pagos debe ser mayor que 1 cuando el tipo es Crédito.");
                    }
                }
            ],

        ]);

        $billData = $validated['form'];
        $billData['branch_id'] = Auth::user()->active_branch_id;
        $billData['emission_date'] = Carbon::parse($billData['emission_date'] ?? now());
        $billData['expiration_date'] = $billData['expiration_date'] ? Carbon::parse($billData['expiration_date']) : null;
        $bill = Bill::create($billData);
        $details = collect($validated['details'])->map(function ($detail) use ($bill) {
            $detail['branch_id'] = $bill->branch_id;
            $detail['doctor_id'] = $bill->doctor_id;
            return $detail;
        })->toArray();
        $billDetails = $bill->billdetail()->createMany($details);

        foreach ($billDetails as $detail) {
            $expense_amount = $detail->amount_doctor;

            $expense_amount = $detail->amount_doctor + ($detail->materials_amount / 2);

            if ($detail->doctor->specialty) {
                Expenses::create([
                    'description' => $detail->doctor->first_name,
                    'amount'      => $expense_amount,
                    'active'      => true,
                    'user_id'     => Auth::id(),
                    'branch_id'   => $detail->branch_id,
                    'doctor_id' => $bill->doctor_id
                ]);
            } else {
                PendingExpense::create([
                    'description' => $detail->doctor->first_name,
                    'amount'      => $expense_amount,
                    'user_id'     => Auth::id(),
                    'branch_id'   => $detail->branch_id,
                    'doctor_id' => $bill->doctor_id

                ]);
            }
        }

        $initialSum = $billDetails->sum('initial');

        if ($bill->total > $initialSum) {
            $patient = $bill->patient;
            if (!$patient->cxc) {
                $CXC = CXC::create([
                    'balance' => $bill->total,
                    'patient_id' => $bill->patient_id,
                    'doctor_id' => $bill->doctor_id,
                    'branch_id'  => $bill->branch_id,
                ]);
                $bill->c_x_c_id = $CXC->id;
                $bill->save();
            } else {
                $CXC = $patient->cxc;
                $bill->c_x_c_id = $CXC->id;
                $bill->save();
                $CXC->balance += $bill->total;
                $CXC->save();
            }
            $bill->load(['billdetail', 'doctor', 'patient', 'cxc']);

            return response()->json([
                'redirect' => route('bills.show', $bill->id),
            ]);
        }


        $bill->load(['billdetail', 'doctor', 'patient', 'cxc']);



        return response()->json([
            'redirect' => route('bills.show', $bill->id),
        ]);
    }
    public function show(Bill $bill)
    {
        $bill->load('patient', 'billdetail.procedure', 'branch');
        return Inertia::render('Bills/Show', [
            'bill' => $bill,


        ]);
    }

    public function edit(Bill $bill)
    {
        $this->authorize('update', Bill::class);
        $bill->load('patient', 'billdetail.procedure', 'branch');
        $patients = User::role('patient')->paginate(10);
        $procedure = Procedure::paginate(10);
        $doctors = User::role('doctor')->with('roles')->paginate(10);

        return Inertia::render('Bills/Edit', [
            'bill' => $bill,
            'patients' => $patients,
            'doctors' => $doctors,
            'procedure' => $procedure,
        ]);
    }

    public function update(Request $request, Bill $bill)
    {
        $this->authorize('update', Bill::class);

        if ($request->has('active')) {
            $this->restore($bill);
            return redirect()->back()->with('toast', 'Factura restaurada correctamente');
        }
        $data = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'emission_date' => 'required|date',
            'expiration_date' => 'nullable|date',
            'total' => 'required|numeric',
            'amount_of_payments' => 'nullable|numeric',
            'doctor_id' => 'required|exists:users,id',
            'currency' => 'required|string',
        ]);

        $bill->update($data);

        return redirect()->route('bills.show', $bill)->with('toast', 'Factura actualizada correctamente.');
    }

    public function destroy(Bill $bill)
    {
        $this->authorize('delete', Bill::class);

        DB::transaction(function () use ($bill) {

            $bill->load('billdetail', 'payments', 'cxc');

            $bill->active = 0;

            $CXC = $bill->cxc;

            $totalAjuste = 0;

            foreach ($bill->billdetail as $billdetail) {
                $billdetail->active = 0;
                $billdetail->save();

                if ($bill->type === "Credito" && $CXC) {
                    $totalAjuste += $billdetail->total;
                }
            }


            foreach ($bill->payments as $payment) {
                $payment->active = 0;
                $payment->save();
            }
              Log::info($bill->type);
            if ($bill->type === "Credito" && $CXC) {
                Log::info($totalAjuste);
                $CXC->balance -= $totalAjuste;
                Log::info($CXC->balance);
                $CXC->save();
            }


            $bill->save();
        });


        return redirect()->back()
            ->with('toast', 'Factura eliminada correctamente');
    }
    public function restore(Bill $bill)
    {
        DB::transaction(function () use ($bill) {

            $bill->load('billdetail', 'payments', 'cxc');

            $bill->active = 1;

            $CXC = $bill->cxc;

            $totalAjuste = 0;

            foreach ($bill->billdetail as $billdetail) {
                $billdetail->active = 1;
                $billdetail->save();

                if ($bill->type === "Credito" && $CXC) {
                    $totalAjuste += $billdetail->total;
                }
            }

            foreach ($bill->payments as $payment) {
                $payment->active = 1;
                $payment->save();
            }

            if ($bill->type === "Credito" && $CXC) {
                $CXC->balance += $totalAjuste;
                $CXC->save();
            }

            $bill->save();
        });


        return redirect()->back()->with('toast', 'Factura restaurada correctamente');
    }
}
