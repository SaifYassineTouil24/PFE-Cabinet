<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PatientController extends Controller
{
    public function show(string $id)
    {
        $patient = Patient::with('Appointment')
            ->where('ID_patient', $id)
            ->firstOrFail();

        $lastAppointment = $patient->Appointment->sortByDesc('appointment_date')->first();
        $appointementsHistory = $patient->Appointment->sortByDesc('appointment_date');

        return view('patient-details', compact('patient', 'lastAppointment', 'appointementsHistory'));
    }

    public function index(Request $request)
    {
        // Cette ligne détermine si nous devons afficher les archives
        $showArchived = $request->has('archived');
    
        // Amélioration de la requête pour filtrer correctement par statut d'archivage
        $patients = Patient::with('Appointment')
            ->where('archived', $showArchived ? true : false)
            ->get();

        return view('patients', compact('patients', 'showArchived'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_day' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'CIN' => 'required|string|max:255|unique:patients,CIN',
            'phone_num' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'mutuelle' => 'nullable|in:CNSS,CNOPS',
            'allergies' => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['archived'] = false;

        $patient = Patient::create($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient ajouté avec succès!');
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_day' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'CIN' => 'required|string|max:255|unique:patients,CIN,' . $id . ',ID_patient',
            'phone_num' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'mutuelle' => 'nullable|in:CNSS,CNOPS',
            'allergies' => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient mis à jour avec succès!');
    }

    public function updatee(Request $request, $id)
    {
        $patient = Patient::where('ID_patient', $id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_day' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'CIN' => 'required|string|max:255|unique:patients,CIN,' . $id . ',ID_patient',
            'phone_num' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'mutuelle' => 'nullable|string',
            'allergies' => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->back()
            ->with('success', 'Patient mis à jour avec succès!');
    }

    public function archive(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $request->validate([
            'archived' => 'required|boolean'
        ]);

        $patient->update([
            'archived' => $request->archived
        ]);

        // Ajouter un paramètre indiquant si nous étions sur la page d'archive
        $redirectParams = $request->has('fromArchived') && $request->fromArchived ? ['archived' => true] : [];

        return response()->json([
            'success' => true,
            'archived' => $patient->archived,
            'message' => $request->archived ? 'Patient archivé avec succès' : 'Patient désarchivé avec succès',
            'redirect' => route('patients.index', $redirectParams)
        ]);
    }

    public function search(Request $request)
    {
        $term = $request->query('term');
        
        if (empty($term)) {
            return response()->json([]);
        }
        
        $patients = Patient::where('name', 'LIKE', "%{$term}%")
            ->where('archived', false) // Ne retourner que les patients non archivés
            ->select('ID_patient as id', 'name')
            ->limit(10)
            ->get();
        
        return response()->json($patients);
    
}


public function modal()
            {
                  return view('profile.partials.add-patient-modal');
}

}

