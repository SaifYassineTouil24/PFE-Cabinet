<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class MedecinController extends Controller
{
    public function dashboard()
    {
        try {
            $today = now()->format('Y-m-d');

            // Récupérer le patient en consultation (uniquement pour aujourd'hui)
            $currentPatient = Appointment::with(['patient', 'caseDescription', 'medicaments', 'analyses'])
                ->where('status', 'En consultation')
                ->whereDate('appointment_date', $today)
                ->latest()
                ->first();

            // Vérifier s'il y a un patient en mode "visualisation" dans la session
            $viewingPatientId = Session::get('viewing_patient_id');
            $viewingMode = Session::get('viewing_mode', false);
            
            if ($viewingMode && $viewingPatientId) {
                $viewingPatient = Appointment::with('patient', 'caseDescription', 'medicaments', 'analyses')
                    ->whereDate('appointment_date', $today)
                    ->find($viewingPatientId);
                    
                if ($viewingPatient) {
                    $currentPatient = $viewingPatient;
                }
            } else if (!$currentPatient) {
                // Si aucun patient n'est en consultation et pas en mode visualisation, 
                // prendre alors le dernier patient terminé d'aujourd'hui
                $currentPatient = Appointment::with('patient', 'caseDescription', 'medicaments', 'analyses')
                    ->where('status', 'Terminé')
                    ->whereDate('appointment_date', $today)
                    ->latest()
                    ->first();
            }
            
            // Récupérer le dernier rendez-vous du patient actuel (pour la section consultation)
            $lastAppointment = null;
            if ($currentPatient && $currentPatient->patient) {
                $patientAppointments = $currentPatient->patient->Appointment()
                    ->with(['caseDescription', 'medicaments', 'analyses'])
                    ->orderBy('appointment_date', 'desc')
                    ->get();
                
                $lastAppointment = $patientAppointments->first();
            }
            
            // Récupérer les patients en salle d'attente (aujourd'hui seulement)
            $waitingPatients = Appointment::with('patient')
                ->where('status', 'Salle dattente')
                ->whereDate('appointment_date', $today)
                ->latest()
                ->get();

            // Récupérer les patients en préparation (aujourd'hui seulement)
            $preparingPatients = Appointment::with('patient')
                ->where('status', 'En préparation')
                ->whereDate('appointment_date', $today)
                ->latest()
                ->get();

            // CORRECTION: Récupérer les patients terminés AUJOURD'HUI seulement
            $completedTodayPatients = Appointment::with('patient')
                ->where('status', 'Terminé')
                ->whereDate('appointment_date', $today) // Filtrer par date de rendez-vous d'aujourd'hui
                ->latest('updated_at') // Trier par heure de fin de consultation
                ->get();
                








            // Calcul du temps moyen de consultation (aujourd'hui seulement)
            $averageTime = $this->calculateAverageTime($completedTodayPatients);
            
            // Obtenir le nombre total de patients
            $totalPatients = Patient::count();
            
            // Obtenir le nombre de patients aujourd'hui
            $todayPatients = Appointment::whereDate('appointment_date', $today)->count();
            
            // Obtenir le nombre de rendez-vous en cours aujourd'hui
            $activeAppointments = Appointment::whereDate('appointment_date', $today)
                ->whereIn('status', ['Salle dattente', 'En préparation', 'En consultation'])
                ->count();

            return view('medecin', compact(
                'currentPatient',
                'lastAppointment',
                'waitingPatients',
                'preparingPatients',
                'completedTodayPatients', // NOUVEAU: Passer les patients terminés d'aujourd'hui
                'averageTime',
                'totalPatients',
                'todayPatients',
                'activeAppointments',
                'viewingMode'
            ));

        } catch (\Exception $e) {
            Log::error('Erreur dans MedecinController@dashboard: ' . $e->getMessage());

            return view('medecin', [
                'currentPatient' => null,
                'lastAppointment' => null,
                'waitingPatients' => collect(),
                'preparingPatients' => collect(),
                'completedTodayPatients' => collect(), // NOUVEAU: Collection vide par défaut
                'averageTime' => 0,
                'totalPatients' => 0,
                'todayPatients' => 0,
                'activeAppointments' => 0,
                'viewingMode' => false
            ])->with('error', 'Une erreur est survenue lors du chargement du dashboard.');
        }
    }

    private function calculateAverageTime($appointments)
    {
        if ($appointments->isEmpty()) return 0;
    
        $totalMinutes = $appointments->sum(function($appointment) {
            // Utilisez created_at et updated_at comme approximation si start_time/end_time n'existent pas
            $start = $appointment->start_time ?? $appointment->created_at;
            $end = $appointment->end_time ?? $appointment->updated_at;
            
            return $end->diffInMinutes($start);
        });
    
        return round($totalMinutes / $appointments->count());
    }

    public function updateStatus(Request $request)
    {
        try {
            $appointment = Appointment::findOrFail($request->appointment_id);
            
            // Vérifier si on essaie de démarrer une consultation alors qu'il y en a déjà une active
            if ($request->status === 'consulting') {
                $activeConsultation = Appointment::where('status', 'En consultation')
                    ->where('ID_RV', '!=', $appointment->ID_RV)
                    ->first();
                    
                if ($activeConsultation) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Il y a déjà un patient en consultation (' . $activeConsultation->patient->name . '). Veuillez terminer cette consultation avant d\'en démarrer une nouvelle.'
                    ], 409); // Code 409 = Conflict
                }
            }
            
            // Mappage des statuts
            $statusMap = [
                'completed' => 'Terminé',
                'canceled' => 'Annulé',
                'preparing' => 'En préparation',
                'consulting' => 'En consultation'
            ];
            
            $appointment->status = $statusMap[$request->status] ?? $request->status;
            $appointment->save();
            
            // Réinitialiser le mode visualisation si on modifie un statut
            Session::forget('viewing_patient_id');
            Session::forget('viewing_mode');

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur dans MedecinController@updateStatus: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut: ' . $e->getMessage()
            ], 500);
        }
    }

    public function navigatePatient(Request $request)
    {
        try {
            $direction = $request->direction; // 'prev' ou 'next'
            $currentAppointmentId = $request->current_appointment_id;
            $today = now()->format('Y-m-d'); // AJOUT: Date d'aujourd'hui
            
            // Si aucun ID de rendez-vous n'est fourni, renvoyer une erreur
            if (!$currentAppointmentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun patient sélectionné pour la navigation'
                ]);
            }
            
            // Récupérer le rendez-vous actuel
            $currentAppointment = Appointment::find($currentAppointmentId);
            
            if (!$currentAppointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rendez-vous actuel introuvable'
                ]);
            }
            
            // Détecter si le patient actuel est en consultation ou en mode visualisation
            $currentIsInConsultation = $currentAppointment->status === 'En consultation';
            $currentIsCompleted = $currentAppointment->status === 'Terminé';
            $viewingMode = Session::get('viewing_mode', false);
            
            // Si on est en mode visualisation ou déjà sur un patient terminé
            if ($viewingMode || $currentIsCompleted) {
                // CORRECTION: Récupérer seulement les rendez-vous terminés d'AUJOURD'HUI
                $completedAppointments = Appointment::where('status', 'Terminé')
                    ->whereDate('appointment_date', $today) // AJOUT: Filtrer par aujourd'hui
                    ->orderBy('updated_at', 'desc') // Tri par date de mise à jour pour avoir les plus récents d'abord
                    ->get();
                
                if ($completedAppointments->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aucun patient terminé disponible aujourd\'hui'
                    ]);
                }
                
                // Trouver l'index du rendez-vous actuel dans le tableau des rendez-vous terminés
                $currentIndex = -1;
                foreach ($completedAppointments as $index => $appointment) {
                    if ($appointment->ID_RV == $currentAppointmentId) {
                        $currentIndex = $index;
                        break;
                    }
                }
                
                // Déterminer le prochain/précédent rendez-vous en fonction de la direction
                $nextIndex = -1;
                if ($direction === 'next') {
                    // Si on demande "suivant" et qu'on était en train de visualiser un patient terminé,
                    // et qu'il y a un patient en consultation, revenir au patient en consultation
                    $activeConsultation = Appointment::where('status', 'En consultation')
                        ->whereDate('appointment_date', $today) // AJOUT: Filtrer par aujourd'hui
                        ->first();
                    if ($activeConsultation && !$currentIsInConsultation) {
                        // Sortir du mode visualisation
                        Session::forget('viewing_patient_id');
                        Session::forget('viewing_mode');
                        
                        return response()->json([
                            'success' => true,
                            'message' => 'Retour au patient en consultation',
                            'appointment_id' => $activeConsultation->ID_RV,
                            'viewing_mode' => false
                        ]);
                    }
                    
                    // Vérifier si nous sommes au dernier patient terminé
                    if ($currentIndex === 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Fin de la liste - Aucun patient suivant disponible',
                            'end_of_list' => true,
                            'direction' => 'next'
                        ]);
                    }
                    
                    // Navigation normale parmi les patients terminés
                    $nextIndex = $currentIndex - 1; // On va au plus récent ensuite (index 0 est le plus récent)
                } else { // 'prev'
                    // Vérifier si nous sommes au premier patient (le plus ancien)
                    if ($currentIndex === $completedAppointments->count() - 1) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Début de la liste - Aucun patient précédent disponible',
                            'end_of_list' => true,
                            'direction' => 'prev'
                        ]);
                    }
                    
                    // Navigation normale
                    $nextIndex = $currentIndex + 1; // On va au plus ancien ensuite (index plus élevé = plus ancien)
                }
                
                if ($nextIndex == -1 || $nextIndex >= $completedAppointments->count()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aucun patient terminé disponible pour la navigation',
                        'end_of_list' => true,
                        'direction' => $direction
                    ]);
                }
                
                $nextAppointment = $completedAppointments[$nextIndex];
                
                // Stocker l'ID du patient à visualiser en session
                Session::put('viewing_patient_id', $nextAppointment->ID_RV);
                Session::put('viewing_mode', true);
                
                return response()->json([
                    'success' => true,
                    'message' => $direction === 'next' ? 'Visualisation du patient suivant' : 'Visualisation du patient précédent',
                    'appointment_id' => $nextAppointment->ID_RV,
                    'viewing_mode' => true
                ]);
            } else {
                // Si on est sur un patient en consultation active et qu'on veut voir l'historique
                if ($direction === 'prev' && $currentIsInConsultation) {
                    // CORRECTION: Récupérer le dernier patient terminé d'AUJOURD'HUI
                    $lastCompleted = Appointment::where('status', 'Terminé')
                        ->whereDate('appointment_date', $today) // AJOUT: Filtrer par aujourd'hui
                        ->latest('updated_at')
                        ->first();
                        
                    if (!$lastCompleted) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Aucun patient terminé disponible aujourd\'hui',
                            'end_of_list' => true,
                            'direction' => 'prev'
                        ]);
                    }
                    
                    // Activer le mode visualisation
                    Session::put('viewing_patient_id', $lastCompleted->ID_RV);
                    Session::put('viewing_mode', true);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Visualisation du dernier patient terminé',
                        'appointment_id' => $lastCompleted->ID_RV,
                        'viewing_mode' => true
                    ]);
                } else if ($direction === 'next' && $currentIsInConsultation) {
                    // Si on est en consultation et on veut aller au suivant, mais il n'y a pas de notion de "suivant" 
                    // pour un patient en consultation active
                    return response()->json([
                        'success' => false,
                        'message' => 'Vous êtes déjà sur le patient actif en consultation',
                        'end_of_list' => true,
                        'direction' => 'next'
                    ]);
                }
                
                // Autres cas non gérés
                return response()->json([
                    'success' => false,
                    'message' => 'Opération non supportée dans ce contexte'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur dans MedecinController@navigatePatient: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la navigation entre patients: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function returnToConsultation()
    {
        try {
            $today = now()->format('Y-m-d'); // AJOUT: Date d'aujourd'hui
            
            // CORRECTION: Récupérer le patient en consultation actif d'AUJOURD'HUI
            $activeConsultation = Appointment::where('status', 'En consultation')
                ->whereDate('appointment_date', $today) // AJOUT: Filtrer par aujourd'hui
                ->first();
            
            if (!$activeConsultation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun patient actuellement en consultation aujourd\'hui'
                ]);
            }
            
            // Sortir du mode visualisation
            Session::forget('viewing_patient_id');
            Session::forget('viewing_mode');
            
            return response()->json([
                'success' => true,
                'message' => 'Retour au patient en consultation',
                'appointment_id' => $activeConsultation->ID_RV
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur dans MedecinController@returnToConsultation: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du retour au patient en consultation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAppointmentsByDate($date)
    {
        $appointments = Appointment::whereDate('date', Carbon::parse($date)->toDateString())->get();

        return response()->json($appointments);
    }
}