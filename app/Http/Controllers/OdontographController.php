<?php

namespace App\Http\Controllers;

use App\Models\Odontograph;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
class OdontographController extends Controller
{

  public function create(Request $request)
{
    $patient = Patient::where('id',$request->id)->first();

    return Inertia::render('Odontograph/Create', compact('patient'));
}

public function store(Request $request)
{

    $data = $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'cavities' => 'required|array',
        'cavities.center' => 'required|integer',
        'cavities.top' => 'required|integer',
        'cavities.bottom' => 'required|integer',
        'cavities.left' => 'required|integer',
        'cavities.right' => 'required|integer',
        'missing' => 'boolean',
        'filter' => 'boolean',
        'root_canal' => 'boolean',
        'cleaning' => 'boolean',
        'active' => 'boolean',
        'veener' => 'nullable|date',
        'graft' => 'nullable|date',
        'sealant' => 'nullable|date',
        'bonding' => 'nullable|date',
        'examinations' => 'nullable|date',
        'fissure_seal' => 'nullable|date',
    ]);

    $data['doctor_id'] = Auth::id();

    Odontograph::create($data);

    return redirect()->route('patients.show', $data['patient_id'])->with('success', 'Odontograma guardado correctamente.');
}


    public function show(Odontograph $odontograph)
    {
        return Inertia::render('odontographs/Show', [
            'odontograph' => $odontograph,
        ]);
    }
    public function edit(Odontograph $odontograph)
    {
        return Inertia::render('odontographs/Edit', [
            'odontograph' => $odontograph
        ]);
    }
     public function destroy($id)
    {
        $odontograph = Odontograph::findOrFail($id);
        $odontograph->active = false;
        $odontograph->save();

        return redirect()->back()->with('message', 'Odontograma desactivado correctamente.');
    }



}
