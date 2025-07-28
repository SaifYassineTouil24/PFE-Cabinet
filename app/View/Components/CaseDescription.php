<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\CaseDescription;
use Illuminate\Support\Facades\Log;

class CaseDescription extends Component
{
    public string $tension;
    public string $pouls;
    public string $temperature;
    public string $poids;
    public string $taille;
    public string $diagnostic;

    /**
     * Crée une nouvelle instance du composant.
     *
     * @param int $appointmentId L'identifiant du rendez-vous.
     */
    public function __construct(int $appointmentId)
    {
        // Récupérer la description du cas pour le rendez-vous donné
        $caseDescription = CaseDescription::where('ID_RV', $appointmentId)->first();

        if ($caseDescription) {
            $this->tension = $caseDescription->blood_pressure ?? 'N/A';
            $this->pouls = (string) ($caseDescription->pulse ?? 'N/A');
            $this->temperature = (string) ($caseDescription->temperature ?? 'N/A');
            $this->poids = (string) ($caseDescription->weight ?? 'N/A');
            $this->taille = (string) ($caseDescription->tall ?? 'N/A');
            $this->diagnostic = $caseDescription->case_description ?? 'N/A';
        } else {
            // Définit les valeurs par défaut si la description n'existe pas
            $this->tension = 'N/A';
            $this->pouls = 'N/A';
            $this->temperature = 'N/A';
            $this->poids = 'N/A';
            $this->taille = 'N/A';
            $this->diagnostic = 'N/A';
        }
    }

    /**
     * Rendre la vue du composant.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.case-description');
    }
}
