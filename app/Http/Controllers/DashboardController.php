<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Models\Branch;
use Carbon\Carbon;
use App\Models\Bill;
use App\Models\Expenses;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $patients = Patient::all();
        $users = User::all();
        $branches = Branch::all();
        $now = Carbon::now();
        $startOfThisWeek = $now->copy()->startOfWeek();
        $endOfThisWeek = $now->copy()->endOfWeek();

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
                    'user' => Auth::user()->load('branch'),
                ]);
            case 'staff':
                return Inertia::render('DashboardStaff', [
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
                    'user' => Auth::user()->load('branch'),
                ]);
                case 'doctor':
                return Inertia::render('DashboardDoctor', [
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
                    'user' => Auth::user()->load('branch'),
                ]);


            default:
                # code...
                break;
        }
    }
}
