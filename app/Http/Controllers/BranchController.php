<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Branch;
use App\Models\Event;
use App\Models\Expenses;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortField = $request->input('sortField');
        $sortDirection = $request->input('sortDirection', 'asc');
        $activeStates = $request->input('activeStates', []);
        $lastDays = $request->input('lastDays', '30');
        $showDeleted = filter_var($request->input('showDeleted', 'true'), FILTER_VALIDATE_BOOLEAN);


        $query = Branch::query()->select('branches.*');
        if ($showDeleted == true) {
            $query->where('active', 1);
        } else {
            $query->where('active', 0);
        }

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->WhereRaw('name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('address LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('phone_number LIKE ?', ['%' . $search . '%'])
                ;
            });
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('branches.updated_at')
                ->latest('branches.created_at');
        }
        if ($lastDays) {
            if (is_numeric($lastDays)) {

                $dateFrom = Carbon::now()->subDays((int) $lastDays)->startOfDay();
                $query->where('created_at', '>=', $dateFrom);
            } else {

                if ($lastDays === 'month') {
                    $dateFrom = Carbon::now()->startOfMonth();
                    $query->where('created_at', '>=', $dateFrom);
                } elseif ($lastDays === 'year') {
                    $dateFrom = Carbon::now()->startOfYear();
                    $query->where('created_at', '>=', $dateFrom);
                }
            }
        }

        $branches = $query->orderByDesc('created_at')->paginate(10);
        return Inertia::render('Branches/Index', [
            'branches' => $branches,
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

    public function switch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id'
        ]);
        $user = $request->user();
        if (!$user->branches()->where('branch_id', $request->branch_id)->exists()) {
            abort(403, "No tienes acceso a esta sucursal.");
        }

        $user->update([
            'active_branch_id' => $request->branch_id,
        ]);
        Log::info($user->active_branch_id);

        return back()->with('toast', 'Sucursal cambiada correctamente')->with('refresh', true);
    }

    public function create()
    {
        return Inertia::render('Branches/Create');
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|unique:branches,name|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
        ]);
        Branch::create($validated);

        return redirect()->route('branches.index')->with('toast', 'Sucursal registrada correctamente.');
    }
    public function edit(Branch $branch)
    {

        return Inertia::render('Branches/Edit', [
            'branch' => $branch
        ]);
    }
    public function Update(Request $request, Branch $branch)
    {

        if ($request->has('active')) {
            $this->restore($branch);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
        ]);
        $branch->update($validated);

        return redirect()->route('branches.index')->with('toast', 'Sucursal registrada correctamente.');
    }

    public function destroy(Branch $branch)
    {

        //  $this->authorize('delete',Branch::class);

        $branch->active = false;
        $branch->save();

        return redirect()->back()->with('toast', 'Sucursal desactivada correctamente.');
    }

    private function restore(Branch $branch)
    {
        //  $this->authorize('update',Branch::class);

        $branch->active = true;
        $branch->save();

        return redirect()->back()->with('toast', 'Sucursal restaurada correctamente.');
    }

    public function show(Branch $branch)
    {
        $events = Event::withoutGlobalScopes()
            ->where('branch_id', $branch->id)
            ->with('doctor')
            ->get();

        $income = Bill::withoutGlobalScopes()
            ->where('branch_id', $branch->id)
            ->whereDate('emission_date', today())
            ->with(['doctor', 'patient'])
            ->get();

        $expenses = Expenses::withoutGlobalScopes()
            ->where('branch_id', $branch->id)
            ->whereDate('created_at', today())
            ->with('doctor')
            ->get();

        return Inertia::render('Branches/Show', [
            'branch'   => $branch,
            'events'   => $events,
            'income'   => $income,
            'expenses' => $expenses,
        ]);
    }
}
