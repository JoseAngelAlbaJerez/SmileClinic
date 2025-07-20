<?php
use App\Http\Controllers\{
    BudgetController,
    BudgetDetailController,
    CXCController,
    EventController,
    ExpensesController,
    OdontographController,
    PatientController,
    PaymentController,
    PrescriptionController,
    ProfileController,
    ReportController
};
use App\Models\{Budget, Expenses, Patient, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

Route::middleware('auth:sanctum')->group(function () {

    // Dashboard data
    Route::get('/dashboard', function (Request $request) {
        $patients = Patient::all();
        $users = User::all();

        $now = Carbon::now();
        $startOfThisWeek = $now->copy()->startOfWeek();
        $endOfThisWeek = $now->copy()->endOfWeek();
        $startOfLastWeek = $now->copy()->subWeek()->startOfWeek();
        $endOfLastWeek = $now->copy()->subWeek()->endOfWeek();

        $incomeThisWeek = Budget::where('type', 'Contado')
            ->whereBetween('created_at', [$startOfThisWeek, $endOfThisWeek])
            ->sum('total');

        $incomeLastWeek = Budget::where('type', 'Contado')
            ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
            ->sum('total');

        $percentageChange = $incomeLastWeek > 0
            ? (($incomeThisWeek - $incomeLastWeek) / $incomeLastWeek) * 100
            : 0;

        $income = Budget::where('type', '=', 'Contado')->orderByDesc('created_at')->with('budgetdetail.procedure', 'patient')->get();
        $income_sum = $income->sum('total');

        $expense = Expenses::orderByDesc('created_at')->get();
        $expense_sum = $expense->sum('amount');

        return response()->json([
            'patients' => $patients,
            'users' => $users,
            'income_sum' => $income_sum,
            'expense_sum' => $expense_sum,
            'income' => $income,
            'expense' => $expense,
            'incomeThisWeek' => $incomeThisWeek,
            'incomeLastWeek' => $incomeLastWeek,
            'percentageChange' => round($percentageChange, 2),
        ]);
    });

    // Recursos protegidos
    Route::apiResource('patients', PatientController::class);
    Route::apiResource('odontographs', OdontographController::class);
    Route::apiResource('events', EventController::class);
    Route::apiResource('expenses', ExpensesController::class);
    Route::apiResource('budgets', BudgetController::class);
    Route::apiResource('budget-details', BudgetDetailController::class);
    Route::apiResource('cxc', CXCController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('prescriptions', PrescriptionController::class);

    // Reportes
    Route::get('reports/patient/{patient}', [ReportController::class, 'patient']);
    Route::get('reports/budget/{budget}', [ReportController::class, 'budget']);
    Route::get('reports/prescription/{prescription}', [ReportController::class, 'prescription']);
    Route::get('reports/expenses/{days}', [ReportController::class, 'expenses']);
});



