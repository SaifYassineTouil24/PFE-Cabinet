<?php
namespace App\Http\Controllers;
use App\Models\Analysis;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AnalysisController extends Controller
{
    // Affiche la liste des analyses
    public function index(Request $request)
    {
        $showArchived = $request->has('archived') && $request->archived == 1;

        if ($showArchived) {
            $analyses = Analysis::where('archived', 1)->paginate(20); // 20 per page
        } else {
            $analyses = Analysis::where('archived', 0)->paginate(20); // 20 per page
        }

        return view('analyses', compact('analyses', 'showArchived'));
    }

    // Ajoute une nouvelle analyse
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'type_analyse' => 'required|string|max:255',
            'departement' => 'required|string',
        ]);

        // Par défaut, non archivé
        $validated['archived'] = 0;

        // Ajout de l'analyse
        try {
            Analysis::create($validated);
        } catch (\Exception $e) {
            return redirect()->route('analyses.index')
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }

        return redirect()->route('analyses.index')->with('success', 'Analyse ajoutée avec succès!');
    }

    // Méthode pour mettre à jour une analyse existante
    public function update(Request $request, $id)
    {
        // Validation des données
        $validated = $request->validate([
            'type_analyse' => 'required|string|max:255',
            'departement' => 'required|string',
        ]);

        // Mise à jour de l'analyse
        try {
            $analyse = Analysis::findOrFail($id);
            $analyse->update($validated);
        } catch (\Exception $e) {
            return redirect()->route('analyses.index')
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }

        return redirect()->route('analyses.index')->with('success', 'Analyse modifiée avec succès!');
    }

    // Méthode pour archiver une analyse
    public function archive($id)
    {
        try {
            $analyse = Analysis::findOrFail($id);
            $analyse->archived = 1;
            $analyse->save();
        } catch (\Exception $e) {
            return redirect()->route('analyses.index')
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }

        return redirect()->route('analyses.index')->with('success', 'Analyse archivée avec succès!');
    }

    // Méthode pour restaurer une analyse
    public function restore($id)
    {
        try {
            $analyse = Analysis::findOrFail($id);
            $analyse->archived = 0;
            $analyse->save();
        } catch (\Exception $e) {
            return redirect()->route('analyses.index')
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }

        return redirect()->route('analyses.index')->with('success', 'Analyse restaurée avec succès!');
    }

    // Méthode pour supprimer une analyse
    public function destroy($id)
    {
        try {
            $analyse = Analysis::findOrFail($id);
            $analyse->delete();
        } catch (\Exception $e) {
            return redirect()->route('analyses.index')
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }

        return redirect()->route('analyses.index')->with('success', 'Analyse supprimée avec succès!');
    }
}
