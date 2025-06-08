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
        $patient = Patient::where('id', $request->id)->first();

        return Inertia::render('Odontograph/Create', compact('patient'));
    }

    public function store(Request $request)
    {

        $data = $request->validate([
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
        $data['patient_id'] = $request->patient_id;
        $data['doctor_id'] = Auth::id();


        Odontograph::create($data);

        return redirect()->route('patients.show', $data['patient_id'])->with('toast', 'Odontograma guardado correctamente.');
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
    public function update(Request $request, Odontograph $odontograph)
    {
        if ($request->has('active')) {
            $this->restore($odontograph);
            return redirect()->back()->with('toast', 'Odontograma restaurado correctamente');
        }
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'DNI' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'ars' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'address' => 'nullable|string|max:255',
            'motive' => 'nullable|string|max:255',
            'complications' => 'boolean',
            'complications_detail' => 'nullable|string',
            'drugs' => 'boolean',
            'drugs_detail' => 'nullable|string',
            'alergies' => 'boolean',
            'alergies_detail' => 'nullable|string',
        ]);

        $odontograph->update($data);

           return redirect()->back()->with('message', 'Odontograma actualizado correctamente.');
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
