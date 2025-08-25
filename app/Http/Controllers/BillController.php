<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\CXC;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Procedure;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
     public function index(Request $request)
    {

        $search = $request->input('search');
        $sortField = $request->input('sortField');
        $sortDirection = $request->input('sortDirection', 'asc');
        $activeStates = $request->input('activeStates', []);
        $lastDays = $request->input('lastDays', '30');
        $showDeleted = filter_var($request->input('showDeleted', 'true'), FILTER_VALIDATE_BOOLEAN);
        $patient_id = $request->input('patient_id');

        $query = Bill::query()->select('bills.*')
        ->join('patients', 'patient_id','=' ,'patients.id');
        if ($showDeleted == true) {
            $query->where('bills.active', 1);
        } else {
            $query->where('bills.active', 0);
        }

        if ($patient_id) {
            $patient = Patient::find($patient_id);
            $createdAtDates = $patient->bill()->pluck('created_at');

            if ($createdAtDates->isNotEmpty()) {
                $query->where('patient_id', $patient_id)
                    ->whereIn('bills.created_at', $createdAtDates)
                    ->latest();
            }
        }

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->WhereRaw('patients.first_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('patients.last_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('patients.date_of_birth LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('patients.ars LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('patients.date_of_birth LIKE ?', ['%' . $search . '%']);
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

        $bills = $query->orderByDesc('created_at')->with('doctor', 'patient')->paginate(10);
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
     public function create()
    {
        $patient = Patient::with('budget.patient','budget.budgetdetail.procedure')->paginate(10);
        $procedure = Procedure::paginate(10);
        $doctors = User::role('doctor')->with('roles')->paginate(10);

        return Inertia::render('Bills/Create', [
            'patient' => $patient,
            'doctors' => $doctors,
            'procedure' => $procedure,
        ]);
    }
     public function store(Request $request)
    {
        $validated = $request->validate([
            'form.patient_id' => 'required|exists:patients,id',
            'form.type' => 'required|string',
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

        $billData = $validated['form'];
        $billData['emission_date'] = Carbon::parse($billData['emission_date'] ?? now());
        $billData['expiration_date'] = $billData['expiration_date'] ? Carbon::parse($billData['expiration_date']) : null;
        $billData['doctor_id'] = Auth::id();
        $bill = Bill::create($billData);
        $billDetails  = $bill->billdetail()->createMany($validated['details']);
        if ($bill->type == "Crédito") {
            $patient = $bill->patient;

            if (!$patient->cxc) {
                $CXC = CXC::create([
                    'balance' => $bill->total,
                    'patient_id' => $bill->patient_id,
                    'doctor_id' => $bill->doctor_id,
                ]);
            } else {
                $CXC = $patient->cxc;
                $CXC->balance += $bill->total;
                $CXC->save();
            }
            foreach ($billDetails as $detail) {

                $remaining_amount = $detail->total / $detail->amount_of_payments;
                for ($i = 0; $i < $detail->amount_of_payments; $i++) {
                    Payment::create([
                        'bill_detail_id' => $detail->id,
                        'c_x_c_id' => $CXC->id,
                        'amount_paid' => 0,
                        'expiration_date' => $billData['expiration_date']->addMonth(),
                        'remaining_amount' => $remaining_amount,
                        'total' => $detail->total,
                    ]);
                }
            }
            $bill->c_x_c_id = $CXC->id;
            $bill->save();
        }


        $bill->load(['billdetail', 'doctor', 'patient', 'CXC']);



        return response()->json([
            'bill_id' => $bill->id,
        ]);
    }
      public function show(Bill $bill)
    {
        $bill->load('doctor', 'patient', 'billdetail.procedure', 'CXC', 'billdetail.payment');
        return Inertia::render("Bills/Show", [
            'bills' => $bill,
        ]);
    }

}
