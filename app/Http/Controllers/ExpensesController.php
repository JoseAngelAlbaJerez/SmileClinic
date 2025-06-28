<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends Controller
{

    public function index(Request $request)
    {

        $search = $request->input('search');
        $sortField = $request->input('sortField');
        $sortDirection = $request->input('sortDirection', 'asc');
        $activeStates = $request->input('activeStates', []);
        $lastDays = $request->input('lastDays', '30');
        $showDeleted = filter_var($request->input('showDeleted', 'true'), FILTER_VALIDATE_BOOLEAN);


        $query = Expenses::query()
            ->select('expenses.*')
            ->join('users', 'expenses.user_id', '=', 'users.id');

        if ($showDeleted) {
            $query->where('expenses.active', 1);
        } else {
            $query->where('expenses.active', 0);
        }

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->WhereRaw('description LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('amount LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('CONCAT(users.name, " ", COALESCE(users.last_name, "")) LIKE ?', ['%' . $search . '%'])

                ;
            });
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('expenses.updated_at')
                ->latest('expenses.created_at');
        }
        if ($lastDays) {
            if (is_numeric($lastDays)) {

                $dateFrom = Carbon::now()->subDays((int) $lastDays)->startOfDay();
                $query->where('expenses.created_at', '>=', $dateFrom);
            } else {

                if ($lastDays === 'month') {
                    $dateFrom = Carbon::now()->startOfMonth();
                    $query->where('expenses.created_at', '>=', $dateFrom);
                } elseif ($lastDays === 'year') {
                    $dateFrom = Carbon::now()->startOfYear();
                    $query->where('expenses.created_at', '>=', $dateFrom);
                }
            }
        }

        $expenses = $query->orderByDesc('created_at')->with('user')->paginate(10);

        return Inertia::render('Expenses/Index', [
            'expenses' => $expenses,
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

        return Inertia::render("Expenses/Create");
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',

        ]);
        Expenses::create([
            ...$validated,
            'user_id' => Auth::id(),
            'active' => true
        ]);

        return redirect()->back()->with('toast', 'Egreso registrado correctamente.');
    }
    public function update(Request $request, Expenses $expense)
    {
        if ($request->has('active')) {
            $this->restore($expense);
            return redirect()->back()->with('toast', 'Egreso restaurado correctamente.');
        }
    }
    public function destroy(Expenses $expense)
    {
        $expense->active = 0;
        $expense->save();

        return redirect()->back()->with('toast', 'Egreso eliminado correctamente.');
    }
    private function restore(Expenses $expense)
    {
        $expense->active = 1;
        $expense->save();

        return redirect()->back()->with('toast', 'Egreso restaurado correctamente.');
    }
}
