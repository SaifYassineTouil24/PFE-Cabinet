<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Médecin - Clinic Propre</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
   <style>


.swal-compact {
    font-size: 14px !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.swal-compact-title {
    font-size: 18px !important;
    font-weight: 600 !important;
    color: #374151 !important;
    margin-bottom: 8px !important;
}

.swal-compact-content {
    font-size: 14px !important;
    color: #6b7280 !important;
    margin-bottom: 16px !important;
}

.swal-confirm-btn {
    font-size: 13px !important;
    padding: 8px 16px !important;
    border-radius: 6px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
}

.swal-confirm-btn:hover {
    background-color: #b91c1c !important;
    transform: translateY(-1px) !important;
}

.swal-cancel-btn {
    font-size: 13px !important;
    padding: 8px 16px !important;
    border-radius: 6px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
}

.swal-cancel-btn:hover {
    background-color: #4b5563 !important;
    transform: translateY(-1px) !important;
}

.swal-processing {
    font-size: 13px !important;
}

/* Animation pour l'icône de warning */
.swal2-icon.swal2-warning {
    border-color: #f59e0b !important;
    color: #f59e0b !important;
    animation: swal-warning-pulse 2s infinite !important;
}

@keyframes swal-warning-pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Responsive pour mobile */
@media (max-width: 640px) {
    .swal-compact {
        width: 90% !important;
        margin: 0 auto !important;
    }
    
    .swal-compact-title {
        font-size: 16px !important;
    }
    
    .swal-compact-content {
        font-size: 13px !important;
    }
}





        .transition-height {
            transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
            overflow: hidden;
        }
        
        .wait-cursor {
            cursor: wait;
        }
        
        .action-button {
            transition: transform 0.2s ease;
        }
        
        .action-button:hover {
            transform: scale(1.1);
        }
        
        @keyframes refresh-pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        .refreshing {
            animation: refresh-pulse 1s infinite;
        }
        
        .stats-card {
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            padding: 0.5rem;
            height: 50 px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .nav-arrow.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }
        
        .stats-icon {
            font-size: 1rem;
            opacity: 0.8;
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .stats-value {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0;
            line-height: 1.2;
        }
        
        .stats-label {
            font-size: 0.65rem;
            opacity: 0.8;
            margin-bottom: 0.05rem;
            line-height: 1.1;
            letter-spacing: -0.05px;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .stats-date {
            font-size: 0.55rem;
            line-height: 1;
            margin-top: 0.1rem;
        }
        
        .stats-content {
            position: relative;
            padding-right: 1rem;
        }

        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            z-index: 10;
        }

        .nav-arrow:hover {
            background-color: rgba(240, 240, 240, 0.95);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        .nav-arrow.left {
            left: -15px;
        }

        .nav-arrow.right {
            right: -15px;
        }

        .nav-arrow.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
            background-color: rgba(220, 220, 220, 0.8);
        }

        .nav-arrow {
            transition: opacity 0.3s ease, background-color 0.3s ease, transform 0.2s ease;
        }

        .nav-arrow:active:not(.disabled) {
            transform: translateY(-50%) scale(0.95);
        }

        body.wait-cursor {
            cursor: wait !important;
        }

        body.wait-cursor * {
            cursor: wait !important;
        }

        .patient-list-section {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .patient-list-container {
            flex-grow: 1;
            overflow-y: auto;
            max-height: 300px;
        }

        .patient-container {
            position: relative;
        }

        /* Nouveaux styles pour les boutons d'action */
        .action-btn-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            outline: none;
        }

        .action-btn-circle:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .action-btn-circle:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }

        .btn-details {
            background: linear-gradient(135deg,#9f67e4 40%, #490d91 70%);
            color: white;
        }

        .btn-details:hover {
            background: linear-gradient(135deg, #3d0a7b 40%, #490d91 70%);
        }

        .btn-appointment {
            background: linear-gradient(135deg, #1a9cd9 40%, #1237ae 80%);
            color: white;
        }

        .btn-appointment:hover {
            background: linear-gradient(135deg, #1846c7 0%, #1d46b7 100%);
        }

        .btn-cancel {
            background: linear-gradient(135deg, #e67272 40%, #8f0101 70%);
            color: white;
        }

        .btn-cancel:hover {
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
        }

        .btn-complete {
            background: linear-gradient(135deg, #87f0ac 0%,#035200 100%);
            color: white;
        }

        .btn-complete:hover {
            background: linear-gradient(135deg, #024532 0%, #024532 100%);
        }

        .btn-return {
            background: linear-gradient(135deg, #c77aed 30%, #7c009b 80%);
            color: white;
        }

        .btn-return:hover {
            background: linear-gradient(135deg, #4e0adf 0%, #35058e 100%);
        }

        .action-btn-icon {
            font-size: 1.1rem;
        }

        /* Styles spécifiques pour le centrage parfait des icônes dans les tableaux */
        .table-action-cell {
            text-align: center !important;
            vertical-align: middle !important;
            display: table-cell !important;
        }

        .table-action-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            min-height: 40px;
        }

        .table-action-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .table-action-link:hover {
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .table-action-icon {
            font-size: 14px;
            line-height: 1;
        }

        /* Styles pour assurer l'alignement vertical dans les cellules du tableau */
        .table-row-cell {
            vertical-align: middle !important;
        }
</style>
</head>
<body class="flex h-screen bg-gray-100 m-0 p-0">
    <!-- Sidebar (Fixed) -->
    <x-sidebar class="w-64 fixed left-0 top-0 h-full bg-gray-200 shadow-md" />
    
    <div class="flex-1 flex flex-col pl-64 bg-blue-50">
        <!-- Header (Fixed) -->
        <x-header />

        <!-- Content (Scrollable) -->
        <div class="flex-1 overflow-auto p-4">
            <div class="bg-white rounded-2xl shadow-[0px_8px_20px_rgba(0,0,0,0.15)] p-6 min-h-[calc(100vh-120px)]">
                
                <!-- Section des statistiques ultra-compactes -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-6">
                    <!-- Carte Patients Totaux -->
                    <a href="{{ route('patients.index') }}" class="stats-card bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200">
                        <div class="stats-content">
                            <h3 class="stats-label text-blue-700">Patients totaux</h3>
                            <p class="stats-value text-blue-900">{{ $totalPatients }}</p>
                            <p class="stats-date text-blue-600">Jusqu'à aujourd'hui</p>
                            <div class="stats-icon text-blue-500">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </a>
                    
                   <!-- Carte Patients Aujourd'hui -->
                    <a href="{{ route('appointments.index', ['date' => now()->format('Y-m-d')]) }}" class="stats-card bg-gradient-to-br from-green-50 to-green-100 border border-green-200">
                        <div class="stats-content">
                            <h3 class="stats-label text-green-700">Présences du jour</h3>
                            <p class="stats-value text-green-900">{{ $todayPatients }}</p>
                            <p class="stats-date text-green-600">{{ \Carbon\Carbon::now()->format('d M Y') }}</p>
                            <div class="stats-icon text-green-500">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Carte Rendez-vous Aujourd'hui -->
                    <a href="{{ route('appointments.index', ['date' => now()->format('Y-m-d'), 'status' => 'active']) }}" class="stats-card bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200">
                        <div class="stats-content">
                            <h3 class="stats-label text-purple-700">Consultations planifiées</h3>
                            <p class="stats-value text-purple-900">{{ $activeAppointments }}</p>
                            <p class="stats-date text-purple-600">{{ \Carbon\Carbon::now()->format('d M Y') }}</p>
                            <div class="stats-icon text-purple-500">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Section avec diagramme et patient en consultation, côte à côte -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Diagramme circulaire -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-3 border-b border-gray-200 pb-2 text-center">
                            <i class="fas fa-chart-pie mr-2 text-blue-500"></i>Répartition des patients du jour
                        </h2>
                        <div class="h-64 flex items-center justify-center">
                            <canvas id="patientChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Patient en consultation -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 patient-container">
                        <h2 class="text-lg font-bold text-gray-800 mb-3 border-b border-gray-200 pb-2 text-center">
                            <i class="fas fa-user-md mr-2 text-blue-500"></i>
                            @if(isset($viewingMode) && $viewingMode)
                                Patient terminé (mode visualisation)
                            @else
                                Patient en consultation
                            @endif
                        </h2>
                        
                        @if($currentPatient)
                            <!-- Flèche précédente -->
                            <div class="nav-arrow left" onclick="navigatePatient('prev')">
                                <i class="fas fa-chevron-left"></i>
                            </div>

                            <!-- Statut visuel différent selon le mode -->
                            <div class="{{ $currentPatient->status === 'Terminé' ? 'bg-green-50 border border-green-200' : 'bg-purple-50 border border-purple-200' }} rounded-lg p-4 mb-3">
                                <div class="flex flex-col items-center">
                                    <!-- Photo du patient basée sur le sexe -->
                                    <div class="w-20 h-20 rounded-full overflow-hidden mb-3 border-2 {{ $currentPatient->status === 'Terminé' ? 'border-green-300' : 'border-purple-300' }}">
                                        @if($currentPatient->patient->gender === 'Female')
                                            <img src="{{ asset('images/female-avatar.png') }}" alt="Avatar Femme" class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ asset('images/male-avatar.png') }}" alt="Avatar Homme" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $currentPatient->patient->name }}</h3>
                                    
                                    <!-- Statut avec badge différent selon si on est en mode visualisation ou non -->
                                    @if(isset($viewingMode) && $viewingMode)
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm mb-2">
                                            Consultation terminée
                                        </span>
                                    @else
                                        <span class="{{ $currentPatient->status === 'Terminé' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700' }} px-3 py-1 rounded-full text-sm mb-4">
                                            {{ $currentPatient->status === 'Terminé' ? 'Terminé' : 'En consultation' }}
                                        </span>
                                    @endif
                                
                                    <!-- Boutons d'action (différents selon le statut) - Version améliorée -->
                                    <div class="flex space-x-4 mt-2">
                                        <button 
                                            onclick="window.location.href='{{ route('patient.details', $currentPatient->patient->ID_patient) }}'" 
                                            class="action-btn-circle btn-details" 
                                            title="Détails du patient">
                                            <i class="fas fa-clipboard-list action-btn-icon"></i>
                                        </button>
                                        
                                        <button 
                                            onclick="window.location.href='{{ route('appointments.edit.form', $currentPatient->ID_RV) }}'" 
                                            class="action-btn-circle btn-appointment" 
                                            title="Détails du rendez-vous">
                                            <i class="fas fa-user-edit action-btn-icon"></i>
                                        </button>
                                        
                                        @if(isset($viewingMode) && $viewingMode)
                                            <!-- Bouton spécial en mode visualisation pour revenir au patient en consultation -->
                                            <button type="button" 
                                                    class="action-btn-circle btn-return" 
                                                    onclick="returnToConsultation()"
                                                    title="Retour au patient en consultation">
                                                <i class="fas fa-undo action-btn-icon"></i>
                                            </button>
                                        @elseif($currentPatient->status !== 'Terminé')
                                            <button type="button" 
                                                    class="action-btn-circle btn-cancel" 
                                                    onclick="confirmCancel('{{ $currentPatient->ID_RV }}')"
                                                    title="Annuler la consultation">
                                                <i class="fas fa-times-circle action-btn-icon"></i>
                                            </button>
                                            
                                            <button type="button" 
                                                    class="action-btn-circle btn-complete" 
                                                    onclick="updateAppointmentStatus('{{ $currentPatient->ID_RV }}', 'completed')"
                                                    title="Terminer la consultation">
                                                <i class="fas fa-check-circle action-btn-icon"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Flèche suivante -->
                            <div class="nav-arrow right" onclick="navigatePatient('next')">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center mb-3">
                                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-user-md text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Aucun patient en consultation</h3>
                                <p class="text-gray-500">Sélectionnez un patient de la liste de préparation pour commencer une consultation.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Section Détails du Patient et Dernier Rendez-vous -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mt-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>Détails du Patient
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Détails du patient -->
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            @if($currentPatient)
                                <x-patient-info
                                    :name="$currentPatient->patient->name"
                                    :age="today()->diffInYears($currentPatient->patient->birth_day)"
                                    :sex="$currentPatient->patient->gender"
                                    :phone="$currentPatient->patient->phone_num"
                                    :cin="$currentPatient->patient->CIN"
                                    :mutuelle="$currentPatient->patient->mutuelle ?? 'Aucune'"
                                    :allergies="$currentPatient->patient->allergies ?? 'Aucune'"
                                    :chronic="$currentPatient->patient->chronic_conditions ?? 'Aucune'"
                                    :email="$currentPatient->patient->email ?? 'Aucune'"
                                    :notes="$currentPatient->patient->notes ?? 'Aucune'"                 
                                />
                            @else
                                <p class="text-gray-500 text-center py-4">Aucun patient sélectionné</p>
                            @endif
                        </div>
                        
                        <!-- Dernier rendez-vous -->
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <h3 class="text-lg font-semibold mb-3 text-blue-700">
                                <i class="fas fa-calendar-check mr-2"></i> Dernière Consultation
                            </h3>
                            
                            @if($lastAppointment)
                                <x-last-appo
                                    :date="$lastAppointment->appointment_date ?? ''"
                                    :type="$lastAppointment->type ?? ''"
                                    :diagnostic="$lastAppointment->diagnostic ?? 'Non renseigné'"
                                />
                            @else
                                <p class="text-gray-500">Aucune consultation récente</p>
                            @endif
                            
                            <!-- Bouton pour voir l'historique complet -->
                            @if($currentPatient)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('patient.details', $currentPatient->patient->ID_patient) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-300 text-white rounded-lg hover:bg-blue-600 transition">
                                        <i class="fas fa-history mr-2"></i> Voir l'historique complet
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- NOUVELLE SECTION: Disposition des listes côte à côte -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                    <!-- Section des patients en salle d'attente (Côté gauche) -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-3 patient-list-section">
                        <h2 class="text-base font-bold text-gray-800 mb-2 flex items-center justify-between">
                            <div>
                                <i class="fas fa-clock text-yellow-500 mr-2"></i>
                                Salle d'attente
                                <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-0.5 rounded-full">{{ $waitingPatients->count() }}</span>
                            </div>
                            @if($waitingPatients->count() > 4)
                                <button onclick="togglePatientList('waiting-patients')" class="text-xs text-blue-500 hover:text-blue-700 flex items-center">
                                    <span id="waiting-toggle-text">Voir plus</span>
                                    <i class="fas fa-chevron-down ml-1" id="waiting-toggle-icon"></i>
                                </button>
                            @endif
                        </h2>
                        
                        <div class="patient-list-container">
                            @if($waitingPatients->count() > 0)
                                <div class="space-y-1" id="waiting-patients">
                                    @foreach($waitingPatients as $index => $waiter)
                                        <div class="flex items-center bg-yellow-50 border border-yellow-100 rounded p-1 hover:bg-yellow-100 transition {{ $index >= 4 ? 'hidden' : '' }}">
                                            <div class="w-7 h-7 rounded-full flex items-center justify-center overflow-hidden mr-2">
                                                @if($waiter->patient->gender === 'Female')
                                                    <img src="{{ asset('images/female-avatar.png') }}" alt="Femme" class="w-full h-full object-cover">
                                                @else
                                                    <img src="{{ asset('images/male-avatar.png') }}" alt="Homme" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div class="flex-grow">
                                                <h4 class="text-sm font-medium text-gray-800">{{ $waiter->patient->name }}</h4>
                                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($waiter->appointment_date)->format('H:i') }}</p>
                                            </div>
                                            <div class="flex space-x-1">
                                                <a href="{{ route('patient.details', $waiter->patient->ID_patient) }}" class="text-blue-500 hover:text-blue-700 text-sm" title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button 
                                                    onclick="updateAppointmentStatus('{{ $waiter->ID_RV }}', 'preparing')" 
                                                    class="text-green-500 hover:text-green-700 text-sm" 
                                                    title="Préparer le patient"
                                                >
                                                    <i class="fas fa-chevron-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center p-4 text-gray-500 text-sm">
                                    <p>Aucun patient en attente</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Section des patients en préparation (Côté droit) -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-3 patient-list-section">
                        <h2 class="text-base font-bold text-gray-800 mb-2 flex items-center justify-between">
                            <div>
                                <i class="fas fa-clipboard-list text-orange-500 mr-2"></i>
                                En préparation
                                <span class="ml-2 bg-orange-100 text-orange-800 text-xs font-medium px-2 py-0.5 rounded-full">{{ $preparingPatients->count() }}</span>
                            </div>
                            @if($preparingPatients->count() > 4)
                                <button onclick="togglePatientList('preparing-patients')" class="text-xs text-blue-500 hover:text-blue-700 flex items-center">
                                    <span id="preparing-toggle-text">Voir plus</span>
                                    <i class="fas fa-chevron-down ml-1" id="preparing-toggle-icon"></i>
                                </button>
                            @endif
                        </h2>
                        
                        <div class="patient-list-container">
                            @if($preparingPatients->count() > 0)
                                <div class="space-y-1" id="preparing-patients">
                                    @foreach($preparingPatients as $index => $prep)
                                        <div class="flex items-center bg-orange-50 border border-orange-100 rounded p-1 hover:bg-orange-100 transition {{ $index >= 4 ? 'hidden' : '' }}">
                                            <div class="w-7 h-7 rounded-full flex items-center justify-center overflow-hidden mr-2">
                                                @if($prep->patient->gender === 'Female')
                                                    <img src="{{ asset('images/female-avatar.png') }}" alt="Femme" class="w-full h-full object-cover">
                                                @else
                                                    <img src="{{ asset('images/male-avatar.png') }}" alt="Homme" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div class="flex-grow">
                                                <h4 class="text-sm font-medium text-gray-800">{{ $prep->patient->name }}</h4>
                                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($prep->appointment_date)->format('H:i') }}</p>
                                            </div>
                                            <div class="flex space-x-1">
                                                <a href="{{ route('patient.details', $prep->patient->ID_patient) }}" class="text-blue-500 hover:text-blue-700 text-sm" title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button 
                                                    onclick="updateAppointmentStatus('{{ $prep->ID_RV }}', 'consulting')" 
                                                    class="text-green-500 hover:text-green-700 text-sm" 
                                                    title="Démarrer la consultation"
                                                >
                                                    <i class="fas fa-user-md"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center p-4 text-gray-500 text-sm">
                                    <p>Aucun patient en préparation</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Section des patients terminés - CORRECTION ICI -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-3 mt-4">
                    <h2 class="text-base font-bold text-gray-800 mb-2 flex items-center justify-between">
                        <div>
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Consultations terminées aujourd'hui
                       
                            <span class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full">
                                {{ $completedTodayPatients->count() }}
                            </span>            
                        </div>
                        @if($completedTodayPatients->count() > 2)
                            <button onclick="togglePatientList('completed-patients')" class="text-xs text-blue-500 hover:text-blue-700 flex items-center">
                                <span id="completed-toggle-text">Voir plus</span>
                                <i class="fas fa-chevron-down ml-1" id="completed-toggle-icon"></i>
                            </button>
                        @endif
                    </h2>
                    
                  @if($completedTodayPatients->count() > 0)
    <div class="space-y-1" id="completed-patients">
        @foreach($completedTodayPatients as $index => $completed)
            <div class="flex items-center bg-green-50 border border-green-100 rounded p-1 hover:bg-green-100 transition {{ $index >= 2 ? 'hidden' : '' }}">
                <div class="w-7 h-7 rounded-full flex items-center justify-center overflow-hidden mr-2">
                    @if($completed->patient->gender === 'Female')
                        <img src="{{ asset('images/female-avatar.png') }}" alt="Femme" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('images/male-avatar.png') }}" alt="Homme" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="flex-grow">
                    <h4 class="text-sm font-medium text-gray-800">{{ $completed->patient->name }}</h4>
                    <p class="text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($completed->updated_at)->format('H:i') }}
                        <span class="text-green-600">(Terminé)</span>
                    </p>
                </div>
                <div class="flex space-x-1">
                    <a href="{{ route('patient.details', $completed->patient->ID_patient) }}" class="text-blue-500 hover:text-blue-700 text-sm" title="Voir détails">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('appointments.edit.form', $completed->ID_RV) }}" class="text-blue-500 hover:text-blue-700 text-sm" title="Détails consultation">
                        <i class="fas fa-file-medical"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center p-1 text-gray-500 text-sm">
        <p>Aucune consultation terminée aujourd'hui</p>
    </div>
@endif
                <!-- Section des rendez-vous à venir -->
                <div class="mt-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2">Prochains rendez-vous</h2>
                    
                    @php
                        $upcomingAppointments = \App\Models\Appointment::with('patient')
                            ->where('status', 'Programmé')
                            ->whereDate('appointment_date', '>=', now())
                            ->orderBy('appointment_date')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($upcomingAppointments->count() > 0)
                        <div class="overflow-x-auto rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Patient
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                       <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($upcomingAppointments as $appointment)
                                       <tr>
                                            <td class="px-6 py-4 whitespace-nowrap table-row-cell">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full overflow-hidden flex items-center justify-center mr-3">
                                                        @if($appointment->patient->gender === 'Female')
                                                            <img src="{{ asset('images/female-avatar.png') }}" alt="Femme" class="w-full h-full object-cover">
                                                        @else
                                                            <img src="{{ asset('images/male-avatar.png') }}" alt="Homme" class="w-full h-full object-cover">
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $appointment->patient->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap table-row-cell">
                                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap table-action-cell">
                                                <div class="table-action-container">
                                                    <a href="{{ route('patient.details', $appointment->patient->ID_patient) }}" 
                                                       class="table-action-link text-blue-500 hover:text-blue-700 hover:bg-blue-50" 
                                                       title="Voir patient">
                                                        <i class="fas fa-user table-action-icon"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center p-4 text-gray-500">
                            <p>Aucun rendez-vous programmé à venir</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
       // Fonction pour afficher les toasts
function showWarningToast(message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000, // Un peu plus long pour les messages d'avertissement
        timerProgressBar: true,
        icon: 'warning',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    Toast.fire({
        icon: 'warning',
        title: message
    });
}

// Mise à jour du statut des rendez-vous
function updateAppointmentStatus(appointmentId, status) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    document.body.classList.add('wait-cursor');
    
    fetch('/appointments/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            appointment_id: appointmentId,
            status: status
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                // Si le serveur a renvoyé un message d'erreur spécifique
                if (err.message) {
                    throw new Error(err.message);
                }
                throw new Error('Erreur réseau');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (status === 'completed') {
                showSuccessToast('Consultation terminée avec succès');
            } else if (status === 'canceled') {
                showSuccessToast('Consultation annulée avec succès');
            } else if (status === 'preparing') {
                showSuccessToast('Patient mis en préparation');
            } else if (status === 'consulting') {
                showSuccessToast('Consultation démarrée');
            }
            setTimeout(() => {
                window.location.href = window.location.href;
            }, 1500);
        } else {
            showErrorToast(data.message || 'Une erreur est survenue lors de la mise à jour du statut');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        // Afficher un message différent selon le type d'erreur
        if (error.message.includes('Il y a déjà un patient en consultation')) {
            showWarningToast(error.message);
        } else {
            showErrorToast(error.message || 'Une erreur est survenue lors de la communication avec le serveur');
        }
    })
    .finally(() => {
        document.body.classList.remove('wait-cursor');
    });
}        // Fonction pour naviguer entre les patients
        function navigatePatient(direction) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            document.body.classList.add('wait-cursor');
            
            const leftArrow = document.querySelector('.nav-arrow.left');
            const rightArrow = document.querySelector('.nav-arrow.right');
            
            const clickedArrow = direction === 'prev' ? leftArrow : rightArrow;
            if (clickedArrow) {
                clickedArrow.classList.add('disabled');
            }
            
            fetch('/appointments/navigate-patient', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    direction: direction,
                    current_appointment_id: document.querySelector('input[name="current_appointment_id"]')?.value || '{{ $currentPatient->ID_RV ?? null }}'
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    let message;
                    if (data.viewing_mode) {
                        message = direction === 'next' ? 'Visualisation du patient suivant' : 'Visualisation du patient précédent';
                    } else {
                        message = 'Retour au patient en consultation';
                    }
                    showSuccessToast(message);
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else if (data.end_of_list) {
                    const errorMsg = data.message || (direction === 'next' ? 
                        'Fin de la liste - Aucun patient suivant disponible' : 
                        'Début de la liste - Aucun patient précédent disponible');
                    
                    showErrorToast(errorMsg);
                    
                    if (direction === 'next' && rightArrow) {
                        rightArrow.classList.add('disabled');
                        if (leftArrow) leftArrow.classList.remove('disabled');
                    } else if (direction === 'prev' && leftArrow) {
                        leftArrow.classList.add('disabled');
                        if (rightArrow) rightArrow.classList.remove('disabled');
                    }
                    
                    const patientInfoContainer = document.querySelector('.bg-purple-50, .bg-green-50');
                    if (patientInfoContainer) {
                        patientInfoContainer.classList.add('end-of-list-' + direction);
                        
                        const notificationDiv = document.createElement('div');
                        notificationDiv.className = 'mt-3 p-2 bg-yellow-50 border border-yellow-200 rounded-lg text-center';
                        notificationDiv.innerHTML = `
                            <i class="fas fa-info-circle text-yellow-500 mr-1"></i>
                            <span class="text-yellow-700 text-sm">
                                ${direction === 'next' ? 'Fin de la liste atteinte. Utilisez la flèche gauche pour revenir aux patients précédents.' : 
                                'Début de la liste atteint. Utilisez la flèche droite pour voir les patients suivants.'}
                            </span>
                        `;
                        
                        if (!patientInfoContainer.querySelector('.bg-yellow-50')) {
                            patientInfoContainer.appendChild(notificationDiv);
                        }
                    }
                } else {
                    showErrorToast(data.message || 'Une erreur est survenue lors de la navigation');
                    
                    if (leftArrow) leftArrow.classList.remove('disabled');
                    if (rightArrow) rightArrow.classList.remove('disabled');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showErrorToast('Une erreur est survenue lors de la communication avec le serveur');
                
                if (leftArrow) leftArrow.classList.remove('disabled');
                if (rightArrow) rightArrow.classList.remove('disabled');
            })
            .finally(() => {
                document.body.classList.remove('wait-cursor');
            });
        }

        // Fonction pour revenir au patient en consultation active depuis le mode visualisation
        function returnToConsultation() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            document.body.classList.add('wait-cursor');
            
            const leftArrow = document.querySelector('.nav-arrow.left');
            const rightArrow = document.querySelector('.nav-arrow.right');
            if (leftArrow) leftArrow.classList.remove('disabled');
            if (rightArrow) rightArrow.classList.remove('disabled');
            
            fetch('/appointments/return-to-consultation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                if (!response.ok) {
                    console.error('Erreur HTTP:', response.status, response.statusText);
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Réponse non-JSON:', text);
                            throw new Error(`Erreur ${response.status}: ${text || response.statusText}`);
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccessToast('Retour au patient en consultation');
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showErrorToast(data.message || 'Aucun patient en consultation actuellement');
                }
            })
            .catch(error => {
                console.error('Erreur détaillée:', error);
                showErrorToast('Une erreur est survenue lors de la communication avec le serveur');
            })
            .finally(() => {
                document.body.classList.remove('wait-cursor');
            });
        }

        // Initialiser les flèches après chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            const endOfListNext = localStorage.getItem('end_of_list_next') === 'true';
            const endOfListPrev = localStorage.getItem('end_of_list_prev') === 'true';
            
            const leftArrow = document.querySelector('.nav-arrow.left');
            const rightArrow = document.querySelector('.nav-arrow.right');
            
            if (endOfListNext && rightArrow) {
                rightArrow.classList.add('disabled');
            }
            
            if (endOfListPrev && leftArrow) {
                leftArrow.classList.add('disabled');
            }
        });

        // Fonctions pour afficher les toasts
        function showSuccessToast(message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                icon: 'success',
                title: message
            });
        }

        function showErrorToast(message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                icon: 'error',
                title: message
            });
        }

        // Fonction améliorée pour afficher/masquer les patients supplémentaires
        function togglePatientList(listId) {
            const list = document.getElementById(listId);
            const items = list.querySelectorAll('.hidden');
            const toggleText = document.getElementById(listId === 'waiting-patients' ? 'waiting-toggle-text' : 
                                               (listId === 'preparing-patients' ? 'preparing-toggle-text' : 'completed-toggle-text'));
            const toggleIcon = document.getElementById(listId === 'waiting-patients' ? 'waiting-toggle-icon' : 
                                               (listId === 'preparing-patients' ? 'preparing-toggle-icon' : 'completed-toggle-icon'));
            
            if (items.length > 0 && items[0].classList.contains('hidden')) {
                items.forEach((item, index) => {
                    item.classList.remove('hidden');
                    item.style.maxHeight = '0';
                    item.style.opacity = '0';
                    item.style.overflow = 'hidden';
                    item.style.transition = 'max-height 0.3s ease-in-out, opacity 0.3s ease-in-out';
                    
                    setTimeout(() => {
                        item.style.maxHeight = '100px';
                        item.style.opacity = '1';
                    }, index * 100);
                });
                
                toggleText.textContent = 'Voir moins';
                toggleIcon.classList.remove('fa-chevron-down');
                toggleIcon.classList.add('fa-chevron-up');
            } else {
                const allItems = list.querySelectorAll('.flex.items-center');
                
                for (let i = 2; i < allItems.length; i++) {
                    const item = allItems[i];
                    item.style.maxHeight = '0';
                    item.style.opacity = '0';
                    
                    setTimeout(() => {
                        item.classList.add('hidden');
                        item.style.maxHeight = '';
                        item.style.opacity = '';
                    }, 300);
                }
                
                toggleText.textContent = 'Voir plus';
                toggleIcon.classList.remove('fa-chevron-up');
                toggleIcon.classList.add('fa-chevron-down');
            }
        }

        // Initialisation du diagramme
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('patientChart').getContext('2d');
            
            const todayDate = new Date();
            
            const waitingCount = {{ $waitingPatients->count() }};
            const preparingCount = {{ $preparingPatients->count() }};
const completedCount = {{ \App\Models\Appointment::where('status', 'Terminé')->whereDate('appointment_date', now())->count() }};            
            const patientChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['En attente', 'En préparation', 'Terminé'],
                    datasets: [{
                        data: [waitingCount, preparingCount, completedCount],
                        backgroundColor: [
                            'rgb(250, 204, 21)',
                            'rgb(249, 115, 22)',
                            'rgb(16, 185, 129)'
                        ],
                        borderColor: [
                            'rgb(254, 249, 195)',
                            'rgb(255, 237, 213)',
                            'rgb(236, 253, 245)'
                        ],
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 12
                                },
                                padding: 15
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        });
      function confirmCancel(appointmentId) {
    Swal.fire({
        title: 'Annuler la consultation',
        text: 'Cette action est irréversible.',
        icon: 'warning',
        iconColor: '#f59e0b',
        showCancelButton: true,
        
        // Couleurs professionnelles
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        
        // Textes des boutons
        confirmButtonText: 'Confirmer',
        cancelButtonText: 'Annuler',
        
        // Styles pour rendre plus compact
        width: '400px',
        padding: '1rem',
        
        // Configuration des boutons
        buttonsStyling: true,
        reverseButtons: true,
        
        // Animation plus rapide
        showClass: {
            popup: 'animate__animated animate__fadeIn animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOut animate__faster'
        },
        
        // Style personnalisé
        customClass: {
            popup: 'swal-compact',
            title: 'swal-compact-title',
            content: 'swal-compact-content',
            confirmButton: 'swal-confirm-btn',
            cancelButton: 'swal-cancel-btn'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Animation de confirmation
            Swal.fire({
                title: 'Annulation en cours...',
                text: 'Veuillez patienter',
                icon: 'info',
                iconColor: '#3b82f6',
                showConfirmButton: false,
                timer: 1000,
                width: '350px',
                padding: '1rem',
                customClass: {
                    popup: 'swal-processing'
                }
            });
            
            updateAppointmentStatus(appointmentId, 'canceled');
        }
    });
}









  document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('#calendar-body > div').forEach(day => {
            day.addEventListener('click', function () {
                const selectedDate = this.getAttribute('data-full-date');
                if (selectedDate) {
                    fetchAppointmentsForDate(selectedDate);
                }
            });
        });
    });

    function fetchAppointmentsForDate(date) {
        fetch(`/medecin/appointments-by-date?date=${date}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('appointments-container').innerHTML = html;
            });
    }
    </script>
</body>