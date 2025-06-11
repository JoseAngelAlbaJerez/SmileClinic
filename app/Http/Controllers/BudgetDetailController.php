<?php

namespace App\Http\Controllers;

use App\Models\BudgetDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BudgetDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    $validated = $request->validate([
        'details' => 'required|array',
        'details.*.budget_id' => 'required|exists:budgets,id',
        'details.*.treatment' => 'required|string|max:100',
        'details.*.amount' => 'required|numeric',
        'details.*.total' => 'required|numeric',
        'details.*.discount' => 'required|integer',
        'details.*.quantity' => 'required|integer',
        'details.*.procedure_id' => 'required|integer',
    ]);

    foreach ($validated['details'] as $detail) {
        $detail['active'] = 1;
        BudgetDetail::create($detail);
    }

    return redirect()->route('budgets.index')->with('toast', 'Presupuesto guardados correctamente');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, BudgetDetail $budgetDetail)
    {
          if ($request->has('active')) {
            $this->restore($budgetDetail);
            return redirect()->back()->with('toast', 'Detalle de Presupuesto restaurado correctamente');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budgetdetail $budgetDetail)
    {
        $budgetDetail->active = 0;
        $budgetDetail->save();

        return redirect()->back()->with('toast', 'Detalle de Presupuesto eliminado correctamente');
    }
    private function restore(Budgetdetail $budgetDetail)
    {
        $budgetDetail->active = 1;
        $budgetDetail->save();

        return redirect()->back()->with('toast', 'Detalle de Presupuesto restaurado correctamente');
    }
}
