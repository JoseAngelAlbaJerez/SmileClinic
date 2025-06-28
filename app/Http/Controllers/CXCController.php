<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\CXC;
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


        $query = CXC::query()->select('c_x_c_s.*')
            ->join('budgets', 'c_x_c_s.patient_id', '=', 'budgets.id');
        if ($showDeleted == true) {
            $query->where('c_x_c_s.active', 1);
        } else {
            $query->where('c_x_c_s.active', 0);
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

        $CXC = $query->orderByDesc('created_at')->with('Patient', 'Budget')->paginate(10);
        return Inertia::render('CXC/Index', [
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
        $query = Budget::with('doctor','patient')
            ->join('users', 'odontographs.doctor_id', '=', 'users.id')
            ->select('budgets.*', 'users.name as doctor_name', 'users.last_name as doctor_last_name');



        if ($showDeleted) {
            $query->where('budgets.active', true);
        } else {
            $query->where('budgets.active', false);
        }

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->WhereRaw('CONCAT(users.name, " ", COALESCE(users.last_name, "")) LIKE ?', ['%' . $search . '%'])
                    ->orWhere('budgets.id', $search);
            });
        }


        $CXC = CXC::with( 'patient', 'budget.budgetdetail.procedure','CXCDetail','budget.budgetdetail.Payment')->find($CXC->id);

        return Inertia::render('CXC/Show', [
            'CXC'=> $CXC,

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
