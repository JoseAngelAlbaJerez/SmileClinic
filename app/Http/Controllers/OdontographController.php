<?php

namespace App\Http\Controllers;

use App\Models\Odontograph;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OdontographController extends Controller
{

    public function create(Request $request)
    {
        $patient = Patient::where('id', $request->id)->first();
        $patient->age = Carbon::parse($patient->date_of_birth)->age;
        return Inertia::render('Odontograph/Create', compact('patient'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'data' => 'required|array',
        ]);
        $validated['branch_id'] = Auth::user()->active_branch_id;
        $validated['doctor_id'] = Auth::id();
        $validated['active'] = true;
        $odontogram = Odontograph::create($validated);

        return redirect()
            ->route('patients.show', $validated['patient_id'])
            ->with('toast', 'Odontograma guardado correctamente.');
    }



    public function show(Odontograph $odontograph)
    {
        return Inertia::render('odontograph/Show', [
            'odontograph' => $odontograph,
        ]);
    }
    public function edit(Odontograph $odontograph)
    {
        $odontograph->load(['patient', 'doctor']);
        return Inertia::render('Odontograph/Edit', [
            'odontographs' => $odontograph
        ]);
    }
    public function update(Request $request, Odontograph $odontograph)
    {
        if ($request->has('active')) {
            $this->restore($odontograph);
            return redirect()->back()->with('toast', 'Odontograma restaurado correctamente');
        }
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'data' => 'required|array',
        ]);

        $odontograph->update($data);

        return redirect()->route('patients.show',$odontograph->patient()->first())->with('toast', 'Odontograma actualizado correctamente.');
    }
    private function restore(Odontograph $odontograph)
    {
        $odontograph->active = 1;
        $odontograph->save();

        return redirect()->back()->with('toast', 'Odontograma restaurado correctamente');
    }

    public function destroy(Odontograph $odontograph)
    {

        $odontograph->active = 0;
        $odontograph->save();

        return redirect()->back()->with('toast', 'Odontograma desactivado correctamente');
    }
}
