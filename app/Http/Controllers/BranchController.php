<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Branch;
use App\Models\Expenses;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        Log::info($request);
        if ($request->has('branch')) {
            $branch = Branch::where('id', '=', $request->branch)->pluck('id');
            $patients = Patient::all();
            $users = User::all();
            $branches = Branch::all();
            $now = Carbon::now();
            $startOfThisWeek = $now->copy()->startOfWeek();
            $endOfThisWeek = $now->copy()->endOfWeek();
            $startOfLastWeek = $now->copy()->subWeek()->startOfWeek();
            $endOfLastWeek = $now->copy()->subWeek()->endOfWeek();

            $incomeThisWeek = Bill::where('type', 'Contado')
                ->where('branch_id', '=', $branch->pluck('id'))
                ->whereBetween('created_at', [$startOfThisWeek, $endOfThisWeek])
                ->sum('total');

            $incomeLastWeek = Bill::where('type', 'Contado')
                ->where('branch_id', '=', $branch->pluck('id'))
                ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
                ->sum('total');


            $percentageChange = $incomeLastWeek > 0
                ? (($incomeThisWeek - $incomeLastWeek) / $incomeLastWeek) * 100
                : 100;


            $income = Bill::where('type', '=', 'Contado')->where('branch_id', '=', $branch->pluck('id'))->orderByDesc('created_at')->with('billdetail.procedure', 'patient')->get();
            $income_sum = $income->sum('total');
            $user =Auth::user();
            $user->branch_id = $request->branch;
            $user->save();

            $expense = Expenses::orderByDesc('created_at')->where('branch_id', '=', $branch->pluck('id'))->get();
            $expense_sum = $expense->sum('amount');

            return Inertia::render('Dashboard', [
                'patients' => $patients,
                'branches' => $branches,
                'branch' => $branch,
                'users' => $users,
                'income_sum' => $income_sum,
                'expense_sum' => $expense_sum,
                'income' => $income,
                'expense' => $expense,
                'incomeThisWeek' => $incomeThisWeek,
                'incomeLastWeek' => $incomeLastWeek,
                'percentageChange' => round($percentageChange, 2),
                'user' => $user->load('branch'),
            ]);
        }
    }
}
