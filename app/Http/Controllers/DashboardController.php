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

        $role = Auth::user()->getRoleNames()[0] ?? null;

        switch ($role) {

            case 'admin':
                $now = Carbon::now();
                $startOfThisWeek = $now->copy()->startOfWeek();
                $endOfThisWeek = $now->copy()->endOfWeek();

                $patients = User::withoutGlobalScopes()->whereHas('roles', function ($q) {
                    $q->where('name', 'patient');
                })->get();
                $users = User::query()->select('users.*')->withoutGlobalScopes()->with('roles', 'branches')
                    ->whereDoesntHave('roles', function ($q) {
                        $q->whereIn('name', ['admin']);
                    })->get();
                $branches = Branch::all();
                $now = Carbon::now();

                $incomeByBranch = Bill::withoutGlobalScopes()
                    ->where('type', 'Contado')
                    ->where('active', true)
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

                $tendencyMonthNames = [
                    "01" => "Enero",
                    "02" => "Febrero",
                    "03" => "Marzo",
                    "04" => "Abril",
                    "05" => "Mayo",
                    "06" => "Junio",
                    "07" => "Julio",
                    "08" => "Agosto",
                    "09" => "Septiembre",
                    "10" => "Octubre",
                    "11" => "Noviembre",
                    "12" => "Diciembre",
                ];

                $tendencyQueries = Bill::withoutGlobalScopes()
                    ->where('type', 'Contado')
                    ->where('active', true)
                    ->whereBetween('created_at', [
                        $now->copy()->startOfYear(),
                        $now->copy()->endOfYear()
                    ])
                    ->selectRaw('DATE_FORMAT(created_at, "%m") as month, SUM(total) as total')
                    ->groupByRaw('DATE_FORMAT(created_at, "%m")')
                    ->orderByRaw('DATE_FORMAT(created_at, "%m")')
                    ->get();

                $tendencyMonthTotals = [];
                for ($m = 1; $m <= 12; $m++) {
                    $key = str_pad($m, 2, '0', STR_PAD_LEFT);
                    $tendencyMonthTotals[$key] = 0;
                }

                foreach ($tendencyQueries as $r) {
                    $tendencyMonthTotals[$r->month] = (float) $r->total;
                }

                $tendencySeries = [];
                foreach ($tendencyMonthTotals as $num => $total) {
                    $tendencySeries[] = [
                        $tendencyMonthNames[$num],
                        $total
                    ];
                }
                $categories = [];
                $series = [];

                foreach ($tendencyMonthTotals as $num => $total) {
                    $categories[] = $tendencyMonthNames[$num];
                    $series[] = $total;
                }


                $procedures = DB::table('bill_details')
                    ->join('bills', 'bill_details.bill_id', 'bills.id')
                    ->join('procedures', 'bill_details.procedure_id', 'procedures.id')
                    ->where('bills.type', 'Contado')
                    ->where('bills.active', true)
                    ->select('procedures.name as procedure_name', DB::raw('SUM(bill_details.amount * COALESCE(bill_details.quantity,1)) as total'))
                    ->groupBy('procedures.name')
                    ->orderByDesc('total')
                    ->get();

                $proceduresLabels = $procedures->pluck('procedure_name')->take(8)->toArray();
                $proceduresSeries = $procedures->pluck('total')->map(fn($v) => round((float)$v, 2))->take(8)->toArray();


                $agendaToday = [];
                if (class_exists(Event::class)) {
                    $agendaToday = Event::withoutGlobalScopes()->whereDate('date', Carbon::today())
                        ->with(['patient', 'doctor', 'branch'])
                        ->orderBy('date')
                        ->get();
                }

                $no_show = [];
                if (class_exists(Event::class)) {
                    $no_show = Event::withoutGlobalScopes()->where('attended', '=', false)
                        ->where('active', true)
                        ->where('date', '<', $now)
                        ->whereBetween('created_at', [$startOfThisWeek, $endOfThisWeek])->get();
                }

                $startOfLastWeek = $now->copy()->subWeek()->startOfWeek();
                $endOfLastWeek = $now->copy()->subWeek()->endOfWeek();


                $incomeThisWeek = Bill::withoutGlobalScopes()->where('type', 'Contado')
                    ->where('active', true)
                    ->whereBetween('created_at', [$startOfThisWeek, $endOfThisWeek])
                    ->sum('total');

                $incomeLastWeek = Bill::withoutGlobalScopes()->where('type', 'Contado')
                    ->where('active', true)
                    ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
                    ->sum('total');


                $percentageChange = $incomeLastWeek > 0
                    ? (($incomeThisWeek - $incomeLastWeek) / $incomeLastWeek) * 100
                    : 100;


                $income = Bill::withoutGlobalScopes()->where('type', '=', 'Contado')->where('active',true)->orderByDesc('created_at')->with('billdetail.procedure', 'patient')->get();
                $income_sum = $income->sum('total');


                $expense = Expenses::withoutGlobalScopes()->where('active',true)->orderByDesc('created_at')->get();
                $expense_sum = $expense->sum('amount');
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
                            'categories' => $categories,
                            'series' => $series,
                        ],
                        'procedimientos' => [
                            'labels' => $proceduresLabels,
                            'series' => $proceduresSeries,
                        ],
                        'agendaHoy' => $agendaToday,
                    ],
                ]);
            case 'receptionist':

                $branchId = Auth::user()->active_branch_id;

                $today = Carbon::today();

                $agendaToday = Event::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                    ->whereDate('date', $today)
                    ->with(['patient', 'doctor', 'branch'])
                    ->orderBy('date')
                    ->get();

                $no_show_today = Event::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                    ->whereDate('date', $today)
                    ->where('attended', false)
                    ->get();

                $newPatientsToday = User::whereHas('roles', fn($q) => $q->where('name', 'patient'))
                    ->whereDate('created_at', $today)
                    ->get();

                $incomeToday = Bill::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                    ->where('type', 'Contado')
                    ->whereDate('created_at', $today)
                    ->sum('total');

                $nextAppointments = Event::when($branchId, fn($q) => $q->where('branch_id', $branchId))
                    ->whereBetween('date', [$today, $today->copy()->addDays(7)])
                    ->with('patient', 'doctor')
                    ->orderBy('date')
                    ->get();

                $doctors = User::role('doctor')->with('branches')->get();

                return Inertia::render('DashboardReceptionist', [
                    'agendaToday' => $agendaToday,
                    'noShowToday' => $no_show_today,
                    'newPatientsToday' => $newPatientsToday,
                    'incomeToday' => $incomeToday,
                    'nextAppointments' => $nextAppointments,
                    'doctors' => $doctors,
                    'user' => Auth::user(),
                ]);
            case 'doctor':

                $doctorId = Auth::id();
                $now = Carbon::now();
                $today = Carbon::today();

                $startOfThisWeek = $now->copy()->startOfWeek();
                $endOfThisWeek = $now->copy()->endOfWeek();

                $todayAppointments = Event::withoutGlobalScopes()
                    ->where('doctor_id', $doctorId)
                    ->whereDate('date', $today)
                    ->with(['patient', 'branch'])
                    ->orderBy('date')
                    ->get();


                $totalRevenue = Bill::where('doctor_id', $doctorId)
                    ->where('type', 'Contado')
                    ->sum('total');
                $totalAppointments = Event::where('doctor_id', $doctorId)
                    ->count();

                $noShowCount = Event::where('doctor_id', $doctorId)
                    ->where('attended', false)
                    ->count();

                $noShowPercentage = $totalAppointments > 0
                    ? round(($noShowCount / $totalAppointments) * 100, 2)
                    : 0;

                $recentPatients = Event::where('doctor_id', $doctorId)
                    ->whereBetween('date', [$startOfThisWeek, $endOfThisWeek])
                    ->with('patient')
                    ->get()
                    ->unique('patient_id')
                    ->take(10)
                    ->values();

                $topProcedures = DB::table('bill_details')
                    ->join('bills', 'bill_details.bill_id', '=', 'bills.id')
                    ->join('procedures', 'bill_details.procedure_id', '=', 'procedures.id')
                    ->where('bills.doctor_id', $doctorId)
                    ->select('procedures.name', DB::raw('COUNT(*) as count'))
                    ->groupBy('procedures.name')
                    ->orderByDesc('count')
                    ->take(5)
                    ->get();

                $days = collect();
                for ($i = 6; $i >= 0; $i--) {
                    $days->push($today->copy()->subDays($i)->format('Y-m-d'));
                }

                $appointmentsTrend = Event::where('doctor_id', $doctorId)
                    ->where('attended', '=', 1)
                    ->whereBetween(DB::raw('DATE(date)'), [
                        $today->copy()->subDays(6)->format('Y-m-d'),
                        $today->format('Y-m-d')
                    ])
                    ->select(DB::raw('DATE(date) as day'), DB::raw('COUNT(*) as total'))
                    ->groupBy('day')
                    ->pluck('total', 'day');

                $appointmentsTrendSeries = $days->map(fn($d) => $appointmentsTrend[$d] ?? 0);

                $appointmentsTrendCategories = $days->toArray();


                return Inertia::render('DashboardDoctor', [
                    'todayAppointments' => $todayAppointments,
                    'totalRevenue' => $totalRevenue,
                    'totalAppointments' => $totalAppointments,
                    'noShowCount' => $noShowCount,
                    'noShowPercentage' => $noShowPercentage,
                    'recentPatients' => $recentPatients,
                    'topProcedures' => $topProcedures,
                    'appointmentsTrendSeries' => $appointmentsTrendSeries,
                    'appointmentsTrendCategories' => $appointmentsTrendCategories,
                    'user' => Auth::user(),
                ]);
            default:
                # code...
                break;
        }
    }
}
