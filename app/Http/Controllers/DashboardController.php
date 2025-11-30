<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Models\Branch;
use Carbon\Carbon;
use App\Models\Bill;
use App\Models\Event;
use App\Models\Expenses;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $now = Carbon::now();
        $startOfThisWeek = $now->copy()->startOfWeek();
        $endOfThisWeek = $now->copy()->endOfWeek();

        $patients = User::whereHas('roles', function ($q) {
            $q->where('name', 'patient');
        })->get();
        $users = User::query()->select('users.*')->with('roles', 'branches')
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['admin']);
            })->get();
        $branches = Branch::all();
        $now = Carbon::now();

        $incomeByBranch = Bill::where('type', 'Contado')
            ->select('branch_id', DB::raw('SUM(total) as total'))
            ->groupBy('branch_id')
            ->get();

        $branchTotals = [];
        foreach ($branches as $b) {
            $branchTotals[$b->id] = 0.0;
        }
        foreach ($incomeByBranch as $row) {
            $branchTotals[$row->branch_id] = (float) $row->total;
        }

        $incomeLabels = [];
        $incomeSeries = [];
        foreach ($branches as $b) {
            $incomeLabels[] = $b->name;
            $incomeSeries[] = round($branchTotals[$b->id] ?? 0, 2);
        }

        $ranking = collect($incomeLabels)
            ->zip($incomeSeries)
            ->map(function ($pair) {
                return ['branch' => $pair[0], 'total' => $pair[1]];
            })
            ->sortByDesc('total')
            ->values()
            ->take(5);

        $days = [];
        $dayTotals = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = $now->copy()->subDays($i);
            $days[] = $d->format('Y-m-d');
            $dayTotals[$d->format('Y-m-d')] = 0.0;
        }
        $tendencyQueries = Bill::where('type', 'Contado')
            ->whereBetween(DB::raw('DATE(created_at)'), [$now->copy()->subDays(6)->toDateString(), $now->toDateString()])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();
        foreach ($tendencyQueries as $r) {
            $dayTotals[$r->date] = (float) $r->total;
        }
        $tendencySeries = array_values($dayTotals);

        $procedures = DB::table('bill_details')
            ->join('bills', 'bill_details.bill_id', 'bills.id')
            ->join('procedures', 'bill_details.procedure_id', 'procedures.id')
            ->where('bills.type', 'Contado')
            ->select('procedures.name as procedure_name', DB::raw('SUM(bill_details.amount * COALESCE(bill_details.quantity,1)) as total'))
            ->groupBy('procedures.name')
            ->orderByDesc('total')
            ->get();

        $proceduresLabels = $procedures->pluck('procedure_name')->take(8)->toArray();
        $proceduresSeries = $procedures->pluck('total')->map(fn($v) => round((float)$v, 2))->take(8)->toArray();


        $agendaToday = [];
        if (class_exists(Event::class)) {
            $agendaToday = Event::whereDate('date', Carbon::today())
                ->with(['patient', 'doctor', 'branch'])
                ->orderBy('date')
                ->get();
        }

        $no_show = [];
        if (class_exists(Event::class)) {
            $no_show = Event::where('attended','=',false)
            ->where('date', '<',$now)
            ->whereBetween('created_at',[$startOfThisWeek, $endOfThisWeek])->get();
        }

        $startOfLastWeek = $now->copy()->subWeek()->startOfWeek();
        $endOfLastWeek = $now->copy()->subWeek()->endOfWeek();


        $incomeThisWeek = Bill::where('type', 'Contado')
            ->whereBetween('created_at', [$startOfThisWeek, $endOfThisWeek])
            ->sum('total');

        $incomeLastWeek = Bill::where('type', 'Contado')
            ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
            ->sum('total');


        $percentageChange = $incomeLastWeek > 0
            ? (($incomeThisWeek - $incomeLastWeek) / $incomeLastWeek) * 100
            : 100;


        $income = Bill::where('type', '=', 'Contado')->orderByDesc('created_at')->with('billdetail.procedure', 'patient')->get();
        $income_sum = $income->sum('total');


        $expense = Expenses::orderByDesc('created_at')->get();
        $expense_sum = $expense->sum('amount');
        $role = Auth::user()->getRoleNames()[0] ?? null;
        switch ($role) {
            case 'admin':
                return Inertia::render('Dashboard', [
                    'patients' => $patients,
                    'branches' => $branches,
                    'users' => $users,
                    'income_sum' => $income_sum,
                    'expense_sum' => $expense_sum,
                    'income' => $income,
                    'expense' => $expense,
                    'incomeThisWeek' => $incomeThisWeek,
                    'incomeLastWeek' => $incomeLastWeek,
                    'percentageChange' => round($percentageChange, 2),
                    'no_show' => $no_show,
                    'user' => Auth::user(),
                    'chart' => [
                        'ingresos' => [
                            'labels' => $incomeLabels,
                            'series' => $incomeSeries,
                        ],
                        'ranking' => $ranking,
                        'tendencia' => [
                            'categories' => $days,
                            'series' => $tendencySeries,
                        ],
                        'procedimientos' => [
                            'labels' => $proceduresLabels,
                            'series' => $proceduresSeries,
                        ],
                        'agendaHoy' => $agendaToday,
                    ],
                ]);
            case 'staff':
                return Inertia::render('Dashboard', [
                    'patients' => $patients,
                    'branches' => $branches,
                    'users' => $users,
                    'income_sum' => $income_sum,
                    'expense_sum' => $expense_sum,
                    'income' => $income,
                    'expense' => $expense,
                    'incomeThisWeek' => $incomeThisWeek,
                    'incomeLastWeek' => $incomeLastWeek,
                    'percentageChange' => round($percentageChange, 2),
                    'user' => Auth::user(),
                ]);
            case 'doctor':
                return Inertia::render('Dashboard', [
                    'patients' => $patients,
                    'branches' => $branches,
                    'users' => $users,
                    'income_sum' => $income_sum,
                    'expense_sum' => $expense_sum,
                    'income' => $income,
                    'expense' => $expense,
                    'incomeThisWeek' => $incomeThisWeek,
                    'incomeLastWeek' => $incomeLastWeek,
                    'percentageChange' => round($percentageChange, 2),
                    'user' => Auth::user(),
                ]);


            default:
                # code...
                break;
        }
    }
}
