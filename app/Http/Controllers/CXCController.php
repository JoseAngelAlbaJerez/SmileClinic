<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\CXC;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
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

        $CXC = $query->orderByDesc('created_at')->with('Patient', 'bills')->paginate(10);



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
    public function show(CXC $CXC, Request $request)
    {
        $showDeleted = filter_var($request->input('showDeleted', 'true'), FILTER_VALIDATE_BOOLEAN);
        $search = $request->input('search');
        $query = Bill::with('doctor', 'patient')
            ->join('users', 'odontographs.doctor_id', '=', 'users.id')
            ->select('bills.*', 'users.name as doctor_name', 'users.last_name as doctor_last_name');



        if ($showDeleted) {
            $query->where('bills.active', true);
        } else {
            $query->where('bills.active', false);
        }

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->WhereRaw('CONCAT(users.name, " ", COALESCE(users.last_name, "")) LIKE ?', ['%' . $search . '%'])
                    ->orWhere('bills.id', $search);
            });
        }


        $CXC = CXC::with('patient', 'bills.billdetail.procedure', 'CXCDetail', 'Payment')->find($CXC->id);

        return Inertia::render('CXC/Show', [
            'CXC' => $CXC,

            'filters' => [
                'search' => $search,
                'showDeleted' => $showDeleted,

            ],
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
