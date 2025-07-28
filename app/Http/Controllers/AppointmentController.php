<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Models\Appointment;
use App\Models\CaseDescription;
use App\Models\Medicament;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
   public function index($date = null)
{
    try {
        // Si aucune date n'est fournie, utiliser la date d'aujourd'hui
        if (!$date) {
            $date = Carbon::now()->format('Y-m-d');
        }

        // Valider et parser la date
        try {
            $parsedDate = Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            // Si la date est invalide, utiliser aujourd'hui
            $parsedDate = Carbon::now()->format('Y-m-d');
            Log::warning("Date invalide fournie: $date, utilisation de la date du jour");
        }

        // Liste des statuts
        $statuses = [
            'Programmé',
            'Salle dattente',
            'En préparation',
            'En consultation',
            'Terminé',
            'Annulé'
        ];

        // Récupérer les rendez-vous du jour donné
        $appointments = Appointment::with('patient')
            ->whereDate('appointment_date', $parsedDate)
            ->orderBy('created_at', 'desc')
            ->get();

        // Grouper les rendez-vous par statut
        $appointmentsByStatus = $appointments->groupBy('status');

        // Statuts individuellement
        $AppoPrograme   = $appointmentsByStatus->get('Programmé', collect());
        $AppoAttend     = $appointmentsByStatus->get('Salle dattente', collect());
        $AppoPrepa      = $appointmentsByStatus->get('En préparation', collect());
        $AppoConsul     = $appointmentsByStatus->get('En consultation', collect());
        $AppoCompleted  = $appointmentsByStatus->get('Terminé', collect());
        $AppoAnnule     = $appointmentsByStatus->get('Annulé', collect());

        // Compter les rendez-vous
        $appointmentCount = $appointments->count();

        // Formater la date pour l'affichage
        $displayDate = Carbon::parse($parsedDate)->locale('fr')->isoFormat('dddd DD MMMM YYYY');

        // Log si en mode debug
        if (config('app.debug')) {
            Log::info("Rendez-vous pour le $parsedDate", [
                'total' => $appointmentCount,
                'par_statut' => $appointmentsByStatus->map->count()
            ]);
        }

        return view('index', compact(
            'AppoPrograme', 'AppoAttend', 'AppoPrepa',
            'AppoConsul', 'AppoCompleted', 'AppoAnnule',
            'appointmentCount', 'parsedDate', 'displayDate'
        ));

    } catch (\Exception $e) {
        Log::error("Erreur dans AppointmentController@index pour la date $date: " . $e->getMessage());

        // En cas d'erreur, utiliser la date d'aujourd'hui
        $fallbackDate = Carbon::now()->format('Y-m-d');
        $displayDate = Carbon::now()->locale('fr')->isoFormat('dddd DD MMMN YYYY');

        return view('index', [
            'AppoPrograme' => collect(),
            'AppoAttend' => collect(),
            'AppoPrepa' => collect(),
            'AppoConsul' => collect(),
            'AppoCompleted' => collect(),
            'AppoAnnule' => collect(),
            'appointmentCount' => 0,
            'parsedDate' => $fallbackDate,
            'displayDate' => $displayDate
        ])->with('error', 'Erreur lors du chargement des rendez-vous pour cette date.');
    }
}
    // In AppointmentController
    public function getMonthlyCounts($yearMonth)
    {
        $appointments = Appointment::where('appointment_date', 'like', "$yearMonth%")
            ->selectRaw('appointment_date, COUNT(*) as count')
            ->groupBy('appointment_date')
            ->pluck('count', 'appointment_date');

        return response()->json($appointments);
    }

    public function updateStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'appointment_id' => 'required|integer|exists:appointments,ID_RV',
                'status' => 'required|string|in:scheduled,waiting,preparing,consulting,completed,canceled'
            ]);

            // Vérifier si on essaie de mettre un patient en consultation
            if ($request->status === 'consulting') {
                // Compter les patients actuellement en consultation (aujourd'hui)
                $currentConsultingCount = Appointment::where('status', 'En consultation')
                    ->whereDate('appointment_date', Carbon::today())
                    ->where('ID_RV', '!=', $request->appointment_id) // Exclure le rendez-vous actuel
                    ->count();

                // Si il y a déjà un patient en consultation, retourner une erreur
                if ($currentConsultingCount >= 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Il y a déjà un patient en consultation. Veuillez terminer la consultation en cours avant d\'en commencer une nouvelle.',
                        'error_type' => 'consultation_limit'
                    ], 422);
                }
            }

            // Mappage des statuts anglais vers français
            $statusMapping = [
                'scheduled' => 'Programmé',
                'waiting' => 'Salle dattente',
                'preparing' => 'En préparation',
                'consulting' => 'En consultation',
                'completed' => 'Terminé',
                'canceled' => 'Annulé'
            ];

            // Utiliser ID_RV pour trouver le rendez-vous
            $appointment = Appointment::where('ID_RV', $request->appointment_id)->firstOrFail();
            $appointment->status = $statusMapping[$request->status];
            $appointment->save();

            // Définir les couleurs correspondant au statut
            $statusColors = [
                'Programmé' => 'bg-blue-100 border-blue-400 text-blue-700',
                'Salle dattente' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
                'En préparation' => 'bg-orange-100 border-orange-400 text-orange-700',
                'En consultation' => 'bg-purple-100 border-purple-400 text-purple-700',
                'Terminé' => 'bg-green-100 border-green-400 text-green-700',
                'Annulé' => 'bg-red-100 border-red-400 text-red-700',
            ];

            $colorClasses = explode(' ', $statusColors[$appointment->status]);

            return response()->json([
                'success' => true,
                'colors' => [
                    'bg' => $colorClasses[0],
                    'border' => $colorClasses[1],
                    'text' => $colorClasses[2]
                ]
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Appointment update error: ' . $e->getMessage());

            // Return a proper error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to update appointment status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function toggleMutuelle(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|integer|exists:appointments,ID_RV',
        ]);

        $appointment = Appointment::findOrFail($validated['appointment_id']);
        $appointment->mutuelle = !$appointment->mutuelle; // Inversion de l'état
        $appointment->save();

        return back()->with('success', 'La case Mutuelle a été mise à jour avec succès.');
    }

    public function editAppointmentDetails(Request $request, $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);

            // Validation
            $validated = $request->validate([
                'case_description' => 'nullable|string',
                'blood_pressure' => 'nullable|string',
                'pulse' => 'nullable|string',
                'temperature' => 'nullable|string',
                'tall' => 'nullable|string',
                'medicaments' => 'nullable|array',
                'medicaments.*.id' => 'required|exists:medicaments,ID_Medicament',
                'medicaments.*.dosage' => 'nullable|string',
                'medicaments.*.frequence' => 'nullable|string',
                'medicaments.*.duree' => 'nullable|string',
                'analyses' => 'nullable|array',
                'analyses.*.id' => 'required|exists:analyses,ID_Analyse',
                'analyses.*.type_analyse' => 'nullable|string',
            ]);

            // Mise à jour ou création de la description du cas
            if ($appointment->caseDescription) {
                $appointment->caseDescription->update([
                    'case_description' => $validated['case_description'] ?? $appointment->caseDescription->case_description,
                    'blood_pressure' => $validated['blood_pressure'] ?? $appointment->caseDescription->blood_pressure,
                    'pulse' => $validated['pulse'] ?? $appointment->caseDescription->pulse,
                    'temperature' => $validated['temperature'] ?? $appointment->caseDescription->temperature,
                    'tall' => $validated['tall'] ?? $appointment->caseDescription->tall,
                ]);
            } else {
                CaseDescription::create([
                    'ID_RV' => $appointment->ID_RV,
                    'case_description' => $validated['case_description'] ?? null,
                    'blood_pressure' => $validated['blood_pressure'] ?? null,
                    'pulse' => $validated['pulse'] ?? null,
                    'temperature' => $validated['temperature'] ?? null,
                    'tall' => $validated['tall'] ?? null,
                ]);
            }

            // Synchroniser les médicaments avec les données du pivot
            if ($request->has('medicaments')) {
                $medicationsSyncData = [];

                foreach ($request->input('medicaments') as $med) {
                    // Validate individual medication first
                    $validator = Validator::make($med, [
                        'id' => 'required|exists:medicaments,ID_Medicament',
                        'dosage' => 'nullable|string',
                        'frequence' => 'nullable|string',
                        'duree' => 'nullable|string',
                    ]);

                    if ($validator->fails()) {
                        continue; // Skip invalid entries or handle errors
                    }

                    $medicationsSyncData[$med['id']] = [
                        'dosage' => $med['dosage'] ?? null,
                        'frequence' => $med['frequence'] ?? null,
                        'duree' => $med['duree'] ?? null,
                    ];
                }

                $appointment->medicaments()->sync($medicationsSyncData);
            } else {
                $appointment->medicaments()->detach();
            }

            if ($request->has('analyses')) {
                $validAnalyses = collect($request->input('analyses'))
                    ->filter(function ($analysis) {
                        return isset($analysis['id']) && !empty($analysis['id']);
                    })
                    ->pluck('id')
                    ->unique()
                    ->toArray();

                $appointment->analyses()->sync($validAnalyses);
            } else {
                $appointment->analyses()->detach();
            }

            return redirect()->back()->with('success', 'Informations mises à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des détails du rendez-vous : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour. Veuillez réessayer.');
        }
    }

    public function showEditForm($id)
    {
        $appointment = Appointment::with(['patient', 'caseDescription', 'medicaments'])->findOrFail($id);

        // Récupérer tous les médicaments disponibles pour le choix
        $availableMedicaments = Medicament::all();
        $availableAnalyses = Analysis::all();

        return view('appointement-details', compact('appointment', 'availableMedicaments', 'availableAnalyses'));
    }

    public function searchMedicaments(Request $request)
    {
        $term = $request->get('q');

        $results = Medicament::where('name', 'like', '%' . $term . '%')->get(['ID_Medicament as id', 'name as text']);

        return response()->json($results);
    }

    public function searchAnalyses(Request $request)
    {
        $term = $request->get('q');

        $results = Analysis::where('type_analyse', 'like', '%' . $term . '%')->get(['ID_Analyse as id', 'type_analyse as text']);

        return response()->json($results);
    }

    /**
     * Méthode privée pour convertir les noms de mois français en numéros
     */
    private function getMonthNumber($monthName)
    {
        $months = [
            'Janvier' => 1,
            'Février' => 2,
            'Mars' => 3,
            'Avril' => 4,
            'Mai' => 5,
            'Juin' => 6,
            'Juillet' => 7,
            'Août' => 8,
            'Septembre' => 9,
            'Octobre' => 10,
            'Novembre' => 11,
            'Décembre' => 12,
        ];
        
        return $months[$monthName] ?? date('m'); // Retourne le mois actuel si non trouvé
    }

    /**
     * Créer un nouveau rendez-vous - Version 1 (plus simple)
     */
    public function storeV1(Request $request)
    {
        try {
            $validated = $request->validate([
                'patient_id' => 'required|integer|exists:patients,ID_patient',
                'type' => 'required|string|in:Consultation,Control',
                'appointment_date' => 'required|date|after_or_equal:today',
                'notes' => 'nullable|string|max:1000'
            ]);

            // Vérifier que le patient n'est pas archivé
            $patient = Patient::where('ID_patient', $validated['patient_id'])
                             ->where('archived', false)
                             ->first();

            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient non trouvé ou archivé'
                ], 404);
            }

            // Créer le rendez-vous
            $appointment = Appointment::create([
                'ID_patient' => $validated['patient_id'],
                'type' => $validated['type'],
                'appointment_date' => $validated['appointment_date'],
                'status' => 'Programmé',
                'mutuelle' => false,
                'notes' => $validated['notes']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rendez-vous créé avec succès',
                'appointment' => $appointment,
                'reload' => true
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du rendez-vous: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du rendez-vous'
            ], 500);
        }
    }

    /**
     * Créer un nouveau rendez-vous - Version 2 (plus complète)
     */
    public function store(Request $request)
    {
        try {
            // Valider d'abord que le patient existe
            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|exists:patients,ID_patient',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le patient sélectionné n\'existe pas dans notre système.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Validation complète des données
            $validated = $request->validate([
                'patient_id' => 'required|exists:patients,ID_patient',
                'appointment_type' => 'required|in:consultation,controle',
                'appointment_date_hidden' => 'required|date_format:Y-m-d', // Utiliser le champ caché avec la date formatée
                'patient_notes' => 'nullable|string',
            ]);

            // Vérifier encore une fois que le patient existe
            $patient = Patient::find($validated['patient_id']);
            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient introuvable. Impossible de créer le rendez-vous.',
                ], 404);
            }

            // Créer un objet DateTime avec midi comme heure par défaut
            $formattedDate = Carbon::createFromFormat('Y-m-d', $validated['appointment_date_hidden'])
                ->setTime(12, 0, 0); // Midi par défaut
            
            // Créer le rendez-vous
            $appointment = Appointment::create([
                'ID_patient' => $validated['patient_id'],
                'appointment_type' => $validated['appointment_type'],
                'appointment_date' => $formattedDate,
                'description' => $validated['patient_notes'] ?? '',
                'status' => 'Programmé',
            ]);

            // Mettre à jour les notes du patient si fournies
            if (!empty($validated['patient_notes'])) {
                if ($patient) {
                    $patient->notes = $validated['patient_notes'];
                    $patient->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Rendez-vous ajouté avec succès pour ' . $patient->name,
                'reload' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur création rendez-vous: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création du rendez-vous: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Rechercher des patients pour l'autocomplete
     */
    public function search(Request $request)
    {
        try {
            $term = $request->get('term', '');
            
            if (strlen($term) < 2) {
                return response()->json([]);
            }
            
            $patients = Patient::where('name', 'like', '%' . $term . '%')
                ->where('archived', false) // Exclure les patients archivés
                ->select('ID_patient as id', 'name', 'phone_num as phone')
                ->limit(10)
                ->get();
                
            return response()->json($patients);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la recherche de patients: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la recherche'], 500);
        }
    }

    /**
     * Afficher le modal d'ajout de patient
     */
    public function modal()
    {
        // Cette méthode retourne le HTML du modal pour l'ajout de patient
        // Vous devez créer une vue pour le modal d'ajout de patient
        return view('modals.add-patient');
    }
}