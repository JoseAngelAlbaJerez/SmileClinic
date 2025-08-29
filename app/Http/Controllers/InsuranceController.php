<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InsuranceController extends Controller
{
    public function store(Request $request)
    {
        Log::info('insurances');

        $validated = $request->validate([
            'ars' => 'required|string|max:255',
            'patient_id' => 'required|exists:patients,id',
            'budget_id' => 'required|exists:budgets,id',
            'affiliate_signature' => 'nullable|string',
            'reclaimer_signature' => 'nullable|string',
        ]);
        $validated['branch_id'] = Auth::user()->branch_id;
        $insurance = Insurance::create($validated);

        return response()->json([
            'insurance_id' => $insurance->id,
            'message' => 'Registro de Seguro registrado correctamente',
        ]);
    }
}
