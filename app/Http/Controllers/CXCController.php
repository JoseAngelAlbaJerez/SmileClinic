<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\CXC;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CXCController extends Controller
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

        $query = CXC::query()->select('c_x_c_s.*')
            ->join('bills', 'c_x_c_s.patient_id', '=', 'bills.id');
        if ($showDeleted == true) {
            $query->where('c_x_c_s.active', 1);
        } else {
            $query->where('c_x_c_s.active', 0);
        }
        if ($patient_id) {
            $patient = Patient::find($patient_id);
            $createdAtDates = $patient->bill()->pluck('created_at');

            if ($createdAtDates->isNotEmpty()) {
                $query->where('c_x_c_s.patient_id', $patient_id)
                    ->whereIn('c_x_c_s.created_at', $createdAtDates)
                    ->latest();
            }
        }

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->WhereRaw('first_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('last_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('bills.type LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('bills.total LIKE ?', ['%' . $search . '%']);
            });
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('c_x_c_s.updated_at')
                ->latest('c_x_c_s.created_at');
        }
        if ($lastDays) {
            if (is_numeric($lastDays)) {

                $dateFrom = Carbon::now()->subDays((int) $lastDays)->startOfDay();
                $query->where('c_x_c_s.created_at', '>=', $dateFrom);
            } else {

                if ($lastDays === 'month') {
                    $dateFrom = Carbon::now()->startOfMonth();
                    $query->where('c_x_c_s.created_at', '>=', $dateFrom);
                } elseif ($lastDays === 'year') {
                    $dateFrom = Carbon::now()->startOfYear();
                    $query->where('c_x_c_s.created_at', '>=', $dateFrom);
                }
            }
        }

        $CXC = $query->orderByDesc('created_at')->with('patient', 'payment', 'bills', 'branch')->paginate(10);



        return Inertia::render('CXC/Index', [
            'CXC' => $CXC,
            'patient_id' => $patient_id,
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

    public function create()
    {

        return Inertia::render("CXC/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CXC $CXC)
    {
       $CXC->load([
    'patient',
    'branch',
    'bills' => function ($q) {
        $q->where('active', 1);
    },
    'bills.billdetail' => function ($q) {
        $q->where('active', 1);
    },
    'bills.billdetail.procedure',
    'bills.payments' => function ($q) {
        $q->where('active', 1);
    },
    'bills.branch',
    'bills.doctor',
    'payment' => function ($q) {
        $q->where('active', 1);
    },
    'payment.bills' => function ($q) {
        $q->where('active', 1);
    },
    'payment.bills.billdetail' => function ($q) {
        $q->where('active', 1);
    },
    'payment.bills.billdetail.procedure',
]);
 return Inertia::render('CXC/Show', [
            'CXC' => $CXC,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->has('active')) {
            $CXC = CXC::find($id);
            $CXC->active = true;
            $CXC->save();
            $CXC->payment()->update(['active' => true]);
            $CXC->bills()->update(['active' => true]);
        return redirect()->route('CXC.index')->with('toast', 'Cuenta por cobrar restaurada correctamente.');

        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CXC $CXC)
    {
        $CXC->active = false;
        $CXC->save();
        $CXC->payment()->update(['active' => false]);
        $CXC->bills()->update(['active' => false]);


        return redirect()->route('CXC.index')->with('toast', 'Cuenta por cobrar eliminada correctamente.');
    }
}
