<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\CXC;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PaymentController extends Controller
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


        $query = Payment::query()->select('payments.*')
            ->join('budget_details', 'payments.budget_detail_id', '=', 'budget_details.id');
        if ($showDeleted == true) {
            $query->where('payments.active', 1);
        } else {
            $query->where('payments.active', 0);
        }

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->WhereRaw('first_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('last_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('budgets.type LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('budgets.total LIKE ?', ['%' . $search . '%']);
            });
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('payments.updated_at')
                ->latest('payments.created_at');
        }
        if ($lastDays) {
            if (is_numeric($lastDays)) {

                $dateFrom = Carbon::now()->subDays((int) $lastDays)->startOfDay();
                $query->where('payments.created_at', '>=', $dateFrom);
            } else {

                if ($lastDays === 'month') {
                    $dateFrom = Carbon::now()->startOfMonth();
                    $query->where('payments.created_at', '>=', $dateFrom);
                } elseif ($lastDays === 'year') {
                    $dateFrom = Carbon::now()->startOfYear();
                    $query->where('payments.created_at', '>=', $dateFrom);
                }
            }
        }

        $CXC = $query->orderByDesc('created_at')->with('Patient', 'Budget')->paginate(10);
        return Inertia::render('Payments/Index', [
            'CXC' => $CXC,
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
    public function create(Request $request)
    {
        $budget = $request->budget_id;

        return Inertia::render('Payments/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $bill = collect($request->patient['bill'])->last();
       $payment = Payment::create([
            'c_x_c_id' => $request->patient['c_x_c']['id'],
            'bill_id'=> $bill['id'] ?? null,
            'total'    => $request->patient['c_x_c']['balance'],
            'amount_paid' => $request->paymentAmount,
            'active' => 1,
            'branch_id' => Auth::user()->branch_id,
        ]);
        $CXC = CXC::find($request->patient['c_x_c']['id']);
        $CXC->balance = $request->balance;
        $CXC->save();


        return redirect()->route('CXC.show',$CXC->id)->with('toast', 'Pago registrado correctamente.');
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
    public function update(Request $request)
    {
        $pagos = $request->input('pagos');

        foreach ($pagos as $pago) {
            $validated = Validator::make($pago, [
                'id' => 'required|exists:payments,id',
                'amount_paid' => 'required|numeric|min:0.01',
                'remaining_amount' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
            ]);

            if ($validated->fails()) {
                return back()
                    ->with('toast', 'Ocurrió un error. ' . $validated->errors())
                    ->with('toastStyle', 'danger');
            }

            $payment = Payment::find($pago['id']);
            $originalAmountPaid = $payment->amount_paid;
            $payment->amount_paid = $pago['amount_paid'];
            $payment->remaining_amount = $pago['remaining_amount'];
            $payment->total = $pago['total'];
            $payment->save();
            $CXC = CXC::find($payment->c_x_c_id);
            $diferencia = $payment->amount_paid - $originalAmountPaid;
            $CXC->balance -= $diferencia;
            $CXC->save();
        }

        return redirect()->back()->with('toast', 'Pago actualizados correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
