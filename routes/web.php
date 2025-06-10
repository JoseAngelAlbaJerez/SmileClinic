<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetDetailController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\OdontographController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Models\BudgetDetail;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('patients', PatientController::class);
    Route::resource('odontographs', OdontographController::class);
    Route::resource('events', EventController::class);
    Route::resource('expenses', ExpensesController::class);
    Route::resource('budgets', BudgetController::class);
        Route::resource('budgetDetails', BudgetDetailController::class);
});

require __DIR__.'/auth.php';
