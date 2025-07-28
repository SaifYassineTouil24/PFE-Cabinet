<?php
namespace App\Http\Controllers;
use App\Models\Medicament;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MedicamentController extends Controller
{
    // Affiche la liste des médicaments
    public function index(Request $request)
    {
        // Filtrer par statut d'archivage (par défaut, afficher les non-archivés)
        $showArchived = $request->has('archived') && $request->archived == 1;
        
        // Récupérer les médicaments selon le filtre
        $medicaments = Medicament::when(!$showArchived, function($query) {
            return $query->where('archived', 0);
        })->when($showArchived, function($query) {
            return $query->where('archived', 1);
        })->get();
        
        return view('medicaments', compact('medicaments', 'showArchived'));
    }

    // Ajoute un nouveau médicament
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'dosage' => 'nullable|string|max:255',
            'composition' => 'nullable|string',
        ]);

        // Par défaut, les nouveaux médicaments ne sont pas archivés
        $validated['archived'] = 0;

        // Ajout du médicament
        try {
            Medicament::create($validated);
        } catch (\Exception $e) {
            return redirect()->route('medicaments.index')
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }

        return redirect()->route('medicaments.index')->with('success', 'Médicament ajouté avec succès!');
    }

    // Méthode pour mettre à jour un médicament existant
    public function update(Request $request, $id)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'dosage' => 'nullable|string|max:255',
            'composition' => 'nullable|string',
        ]);

        // Mise à jour du médicament
        try {
            $medicament = Medicament::findOrFail($id);
            $medicament->update($validated);
        } catch (\Exception $e) {
            return redirect()->route('medicaments.index')
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }

        return redirect()->route('medicaments.index')->with('success', 'Médicament modifié avec succès!');
    }

    // Méthode pour archiver un médicament
    public function archive($id)
    {
        try {
            $medicament = Medicament::findOrFail($id);
            $medicament->archived = 1;
            $medicament->save();
        } catch (\Exception $e) {
            return redirect()->route('medicaments.index')
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }

        return redirect()->route('medicaments.index')->with('success', 'Médicament archivé avec succès!');
    }

    // Méthode pour restaurer un médicament archivé
    public function restore($id)
    {
        try {
            $medicament = Medicament::findOrFail($id);
            $medicament->archived = 0;
            $medicament->save();
        } catch (\Exception $e) {
            return redirect()->route('medicaments.index')
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }

        return redirect()->route('medicaments.index', ['archived' => 1])
            ->with('success', 'Médicament restauré avec succès!');
    }
}