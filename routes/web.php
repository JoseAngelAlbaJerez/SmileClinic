<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetDetailController;
use App\Http\Controllers\CXCController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\OdontographController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');
Route::get('/report/dailycashbalance', [ReportController::class, 'dailycashbalance'])->name('report.dailycashbalance');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/patients/filter', [PatientController::class, 'filter'])->name('patients.filter');
    Route::resource('patients', PatientController::class);
    Route::resource('odontographs', OdontographController::class);
    Route::resource('events', EventController::class);
    Route::resource('expenses', ExpensesController::class);
    Route::resource('budgets', BudgetController::class);
    Route::resource('budgetDetails', BudgetDetailController::class);
    Route::resource('CXC', CXCController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('prescriptions', PrescriptionController::class);
    Route::get('/users/filter', [UserController::class, 'filter'])->name('users.filter');
    Route::resource('users', UserController::class);
    Route::resource('drugs', DrugController::class);
    Route::resource('bills', BillController::class);
    Route::resource('branches', BranchController::class);
    Route::resource('insurances', InsuranceController::class);
    Route::post('/switch-branch', [BranchController::class, 'switch'])
        ->name('branches.switch');



    // Report Modal
    Route::get('/report/fields', [ReportController::class, 'fields'])->name('report.fields');
    Route::get('/report/{table}', [ReportController::class, 'generate'])->name('report.generate');

    //Reports
    Route::get('reports/patient/{patient}', [ReportController::class, 'patient'])->name('report.patient');
    Route::get('reports/CXC/{CXC}', [ReportController::class, 'CXC'])->name('report.CXC');
    Route::get('reports/budget/{budget}', [ReportController::class, 'budget'])->name('report.budget');
    Route::get('reports/budgets/{Days}', [ReportController::class, 'budgets'])->name('report.budgets');
    Route::get('reports/prescription/{prescription}', [ReportController::class, 'prescription'])->name('report.prescription');
    Route::get('reports/expenses/{Days}', [ReportController::class, 'expenses'])->name('report.expenses');
    Route::get('reports/bill/{bill}', [ReportController::class, 'bill'])->name('report.bill');
});

require __DIR__ . '/auth.php';
