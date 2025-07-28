<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Propre - Patients</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        .table-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .patient-row {
            cursor: pointer;
        }
        .add-btn {
            background-color: #678efb;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
            white-space: nowrap;
            margin-left: auto;
        }
        .add-btn:hover {
            background-color: #006de1;
            transform: translateY(-1px);
        }
        .patient-row:hover {
            background-color: rgba(59, 130, 246, 0.05) !important;
        }

        .details-row {
            display: none;
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }
        
        .details-row.active {
            display: table-row;
        }
        
        .details-content {
            padding: 16px;
            border-left: 3px solid #3b82f6;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .archived {
            opacity: 0.7;
            background-color: #f5f5f5;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            z-index: 1000;
            display: none;
        }

        /* Styles pour les icônes d'action sans cadre */
        .action-icon {
            background: none;
            border: none;
            padding: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            border-radius: 4px;
        }
        
        .action-icon:hover {
            background-color: rgba(0, 0, 0, 0.05);
            transform: scale(1.1);
        }
        
        .action-icon.view-btn {
            color: #3b82f6;
        }
        
        .action-icon.view-btn:hover {
            color: #1d4ed8;
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        .action-icon.edit-btn {
            color: #10b981;
        }
        
        .action-icon.edit-btn:hover {
            color: #059669;
            background-color: rgba(16, 185, 129, 0.1);
        }
        
        .action-icon.archive-btn {
            color: #6b7280;
        }
        
        .action-icon.archive-btn:hover {
            color: #374151;
            background-color: rgba(107, 114, 128, 0.1);
        }
        
        .action-icon.archive-btn.archived {
            color: #f59e0b;
        }
        
        .action-icon.archive-btn.archived:hover {
            color: #d97706;
            background-color: rgba(245, 158, 11, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

<!-- Sidebar -->
<x-sidebar class="w-64 fixed left-0 top-0 h-full bg-gray-200 shadow-lg" />

<!-- Main content -->
<div class="flex-1 flex flex-col pl-64">
    <!-- Header -->
    <x-header />
    @if(session('success'))
    <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-4 mt-16" role="alert">
        <strong class="font-bold">Succès !</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif 

    <!-- Toast notification -->
    <div id="toast" class="toast"></div>

    <!-- Page Content -->
    <main class="flex-1 overflow-auto p-8">
        <div class="bg-white p-6 rounded-lg shadow-xl">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 inline-block">
                    <span class="text-blue-700 border-b border-gray-600" style="font-style: italic;">Liste </span> 
                    <span class="text-gray-800">des patients</span>
                </h1>
            </div>
            
            <div class="flex justify-between items-center mb-4">
                <div class="relative w-80">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-sm"></i>
                    </div>
                    <input 
                        type="text" 
                        id="patient-search" 
                        class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full pl-10 p-2
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                        placeholder="Rechercher un patient..."
                    >
                </div>
                
                <div class="flex space-x-2">
                    <button id="show-archived-btn" class="add-btn text-white flex items-center justify-center">
                        <i class="fas fa-archive mr-2"></i>
                        @if($showArchived)
                            Voir patients actifs
                        @else
                            Voir patients archivés
                        @endif
                    </button>
                    <button id="add-patient-btn" class="add-btn text-white flex items-center justify-center">
                        <i class="fas fa-user-plus mr-2"></i>Ajouter un patient
                    </button>
                </div>
            </div>

            <div class="table-container">
                <table class="w-full divide-y divide-gray-200 rounded-lg shadow-lg table-fixed">
                    <thead class="bg-gray-40"> 
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider w-12">     </th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Nom & Prénom</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider w-16">Age</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Dérniere Visite</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Premiere Visite</th>
                            <th class="px-3 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($patients as $patient)
                        <tr class="patient-row hover:bg-gray-100 transition duration-150 @if($patient->archived) archived @endif" data-patient-id="{{ $patient->ID_patient }}">
                            <td class="px-3 py-3 whitespace-nowrap">
                                @if($patient->gender == 'Male')
                                    <img src="{{ asset('images/male-avatar.png') }}" alt="Homme" class="h-8 w-8 rounded-full">
                                @else
                                    <img src="{{ asset('images/female-avatar.png') }}" alt="Femme" class="h-8 w-8 rounded-full">
                                @endif
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-700">{{ $patient->name }}</td>
                            <td class="px-3 py-3 text-sm text-gray-800">{{ \Carbon\Carbon::parse($patient->birth_day)->age }} ans </td>
                            <td class="px-3 py-3 text-sm text-gray-700">
                                {{ $patient->Appointment()->where('appointment_date', '<=', now())->latest('appointment_date')->first()?->appointment_date ? \Carbon\Carbon::parse($patient->Appointment()->where('appointment_date', '<=', now())->latest('appointment_date')->first()?->appointment_date)->format('d/m/Y') : 'Aucune' }}
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-700">
                                {{ $patient->Appointment()->where('appointment_date', '>', now())->orderBy('appointment_date', 'asc')->first()?->appointment_date ? \Carbon\Carbon::parse($patient->Appointment()->where('appointment_date', '>', now())->orderBy('appointment_date', 'asc')->first()?->appointment_date)->format('d/m/Y') : 'Aucune' }}
                            </td>
                            <td class="px-3 py-3 text-center">
                                <div class="flex justify-center space-x-2">
                                    <button class="view-patient-btn action-icon view-btn" 
                                            data-id="{{ $patient->ID_patient }}"
                                            title="Voir note">
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                    <button class="edit-patient-btn action-icon edit-btn"
                                            data-id="{{ $patient->ID_patient }}"
                                            data-name="{{ $patient->name }}"
                                            data-gender="{{ $patient->gender }}"
                                            data-birth-day="{{ $patient->birth_day }}"
                                            data-cin="{{ $patient->CIN }}"
                                            data-phone="{{ $patient->phone_num }}"
                                            data-email="{{ $patient->email }}"
                                            data-mutuelle="{{ $patient->mutuelle }}"
                                            data-allergies="{{ $patient->allergies }}"
                                            data-chronic="{{ $patient->chronic_conditions }}"
                                            data-notes="{{ $patient->notes }}"
                                            title="Modifier patient">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    <button class="archive-patient-btn action-icon archive-btn @if($patient->archived) archived @endif"
                                            data-id="{{ $patient->ID_patient }}"
                                            data-archived="{{ $patient->archived }}"
                                            title="@if($patient->archived) Désarchiver @else Archiver @endif">
                                        <i class="fas @if($patient->archived) fa-undo @else fa-archive @endif text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Ligne des détails -->
                        <tr class="details-row">
                            <td colspan="6" class="px-3 py-3">
                                <div class="details-content">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-600">Nom complet</h3>
                                            <p class="text-gray-900">{{ $patient->name }}</p>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-600">Notes</h3>
                                            <p class="text-gray-800 whitespace-pre-wrap">{{ $patient->notes ?? 'Aucune note' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-3 text-center text-sm text-gray-700">Aucun patient enregistré pour le moment.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal pour Ajouter un patient -->
<div id="patient-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden transition-all duration-300 ease-in-out z-50">
    <div class="bg-white w-full max-w-2xl p-8 rounded-xl shadow-2xl transform transition-all duration-300 ease-in-out max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Ajouter un Patient</h2>
            <button id="close-patient-modal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('patients.store') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom et Prénom</label>
                    <input type="text" name="name" id="name" required placeholder="Nom complet du patient" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Sexe</label>
                    <select name="gender" id="gender" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner</option>
                        <option value="Male">Homme</option>
                        <option value="Female">Femme</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="birth_day" class="block text-sm font-medium text-gray-700">Date de naissance</label>
                    <input type="date" name="birth_day" id="birth_day" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="CIN" class="block text-sm font-medium text-gray-700">CIN</label>
                    <input type="text" name="CIN" id="CIN" required placeholder="Carte d'identité nationale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="phone_num" class="block text-sm font-medium text-gray-700">Téléphone Mobile</label>
                    <input type="tel" name="phone_num" id="phone_num" required placeholder="Numéro de téléphone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" placeholder="Adresse email du patient" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="mutuelle" class="block text-sm font-medium text-gray-700">Mutuelle</label>
                    <select name="mutuelle" id="mutuelle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Aucune</option>
                        <option value="CNSS">CNSS</option>
                        <option value="CNOPS">CNOPS</option>
                    </select>
                </div>
                
                <div class="mb-4 col-span-2">
                    <label for="allergies" class="block text-sm font-medium text-gray-700">Allergies</label>
                    <textarea name="allergies" id="allergies" rows="2" placeholder="Allergies connues du patient" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                
                <div class="mb-4 col-span-2">
                    <label for="chronic_conditions" class="block text-sm font-medium text-gray-700">Maladies chroniques</label>
                    <textarea name="chronic_conditions" id="chronic_conditions" rows="2" placeholder="Conditions médicales chroniques" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                
                <div class="mb-4 col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Notes concernant le patient" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end mt-6">
                <button type="button" id="cancel-patient-btn" class="mr-2 px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">Annuler</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal pour Modifier un patient -->
<div id="edit-patient-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden transition-all duration-300 ease-in-out z-50">
    <div class="bg-white w-full max-w-2xl p-8 rounded-xl shadow-2xl transform transition-all duration-300 ease-in-out max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Modifier le Patient</h2>
            <button id="close-edit-modal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form method="POST" id="edit-patient-form">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Nom et Prénom</label>
                    <input type="text" name="name" id="edit_name" required placeholder="Nom complet du patient" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="edit_gender" class="block text-sm font-medium text-gray-700">Sexe</label>
                    <select name="gender" id="edit_gender" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner</option>
                        <option value="Male">Homme</option>
                        <option value="Female">Femme</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="edit_birth_day" class="block text-sm font-medium text-gray-700">Date de naissance</label>
                    <input type="date" name="birth_day" id="edit_birth_day" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="edit_CIN" class="block text-sm font-medium text-gray-700">CIN</label>
                    <input type="text" name="CIN" id="edit_CIN" required placeholder="Carte d'identité nationale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="edit_phone_num" class="block text-sm font-medium text-gray-700">Téléphone Mobile</label>
                    <input type="tel" name="phone_num" id="edit_phone_num" required placeholder="Numéro de téléphone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="edit_email" placeholder="Adresse email du patient" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="edit_mutuelle" class="block text-sm font-medium text-gray-700">Mutuelle</label>
                    <select name="mutuelle" id="edit_mutuelle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Aucune</option>
                        <option value="CNSS">CNSS</option>
                        <option value="CNOPS">CNOPS</option>
                    </select>
                </div>
                
                <div class="mb-4 col-span-2">
                    <label for="edit_allergies" class="block text-sm font-medium text-gray-700">Allergies</label>
                    <textarea name="allergies" id="edit_allergies" rows="2" placeholder="Allergies connues du patient" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                
                <div class="mb-4 col-span-2">
                    <label for="edit_chronic_conditions" class="block text-sm font-medium text-gray-700">Maladies chroniques</label>
                    <textarea name="chronic_conditions" id="edit_chronic_conditions" rows="2" placeholder="Conditions médicales chroniques" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                
                <div class="mb-4 col-span-2">
                    <label for="edit_notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="edit_notes" rows="3" placeholder="Notes concernant le patient" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end mt-6">
                <button type="button" id="cancel-edit-btn" class="mr-2 px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">Annuler</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>




<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vérifier si nous devons ouvrir le modal directement (après redirection depuis le header)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('openAddModal')) {
            const patientModal = document.getElementById('patient-modal');
            if (patientModal) {
                patientModal.classList.remove('hidden');
            }
        }
    });

    // Gestion de l'expansion des lignes
    document.querySelectorAll('.view-patient-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            const parentRow = button.closest('tr');
            const detailsRow = parentRow.nextElementSibling;
            
            document.querySelectorAll('.details-row').forEach(row => {
                if (row !== detailsRow) {
                    row.classList.remove('active');
                    row.style.maxHeight = '0';
                }
            });
            
            detailsRow.classList.toggle('active');
            
            if (detailsRow.classList.contains('active')) {
                detailsRow.style.maxHeight = `${detailsRow.scrollHeight}px`;
            } else {
                detailsRow.style.maxHeight = '0';
            }
        });
    });

    // Redirection au clic sur la ligne
    document.querySelectorAll('.patient-row').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('.view-patient-btn') || e.target.closest('.edit-patient-btn') || e.target.closest('.archive-patient-btn')) return;
            
            const patientId = this.getAttribute('data-patient-id');
            if (patientId) {
                window.location.href = "{{ route('patient.details', ['id' => ':id']) }}".replace(':id', patientId);
            }
        });
    });

   // Recherche de patients - Version corrigée
document.getElementById('patient-search').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase().trim();
    const tbody = document.querySelector('.table-container table tbody');
    const allRows = tbody.querySelectorAll('tr');
    let visibleCount = 0;
    
    // Supprimer le message "aucun résultat" existant
    const existingNoResults = document.getElementById('no-results-row');
    if (existingNoResults) {
        existingNoResults.remove();
    }
    
    // Parcourir toutes les lignes
    allRows.forEach((row, index) => {
        // Ignorer les lignes de détails et les messages existants
        if (row.classList.contains('details-row') || row.id === 'no-results-row') {
            return;
        }
        
        // Vérifier si c'est une ligne de patient
        if (row.classList.contains('patient-row')) {
            const cells = row.querySelectorAll('td');
            
            // Vérifier qu'on a assez de cellules
            if (cells.length >= 5) {
                // Récupérer les textes des cellules pertinentes
                const patientName = cells[1] ? cells[1].textContent.toLowerCase().trim() : '';
                const age = cells[2] ? cells[2].textContent.toLowerCase().trim() : '';
                const lastVisit = cells[3] ? cells[3].textContent.toLowerCase().trim() : '';
                const nextVisit = cells[4] ? cells[4].textContent.toLowerCase().trim() : '';
                
                // Vérifier si le terme de recherche correspond
                const matches = searchTerm === '' || 
                               patientName.includes(searchTerm) || 
                               age.includes(searchTerm) ||
                               lastVisit.includes(searchTerm) ||
                               nextVisit.includes(searchTerm);
                
                if (matches) {
                    // Afficher la ligne du patient
                    row.style.display = '';
                    
                    // Trouver et masquer la ligne de détails correspondante
                    const nextRow = row.nextElementSibling;
                    if (nextRow && nextRow.classList.contains('details-row')) {
                        
                        nextRow.style.display = 'none';
                        nextRow.classList.remove('active');
                    }
                    
                    visibleCount++;
                } else {
                    // Masquer la ligne du patient
                    row.style.display = 'none';
                    
                    // Trouver et masquer la ligne de détails correspondante
                    const nextRow = row.nextElementSibling;
                    if (nextRow && nextRow.classList.contains('details-row')) {
                        nextRow.style.display = 'none';
                        nextRow.classList.remove('active');
                    }
                }
            }
        }
    });
    
    // Afficher un message si aucun résultat et qu'il y a un terme de recherche
    if (visibleCount === 0 && searchTerm !== '') {
        const noResultsRow = document.createElement('tr');
        noResultsRow.id = 'no-results-row';
        noResultsRow.innerHTML = `
            <td colspan="6" class="px-3 py-8 text-center text-sm text-gray-500">
                <div class="flex flex-col items-center">
                    <i class="fas fa-search text-gray-400 mb-3 text-3xl"></i>
                    <p class="text-lg font-medium text-gray-600">Aucun patient trouvé</p>
                    <p class="text-sm text-gray-400">pour "${searchTerm}"</p>
                </div>
            </td>
        `;
        tbody.appendChild(noResultsRow);
    }
});

    // Gestion du modal d'ajout
    const patientModal = document.getElementById('patient-modal');
    const addPatientBtn = document.getElementById('add-patient-btn');
    const closePatientModal = document.getElementById('close-patient-modal');
    const cancelPatientBtn = document.getElementById('cancel-patient-btn');

    addPatientBtn.addEventListener('click', () => patientModal.classList.remove('hidden'));
    closePatientModal.addEventListener('click', () => patientModal.classList.add('hidden'));
    cancelPatientBtn.addEventListener('click', () => patientModal.classList.add('hidden'));

    // Gestion du modal de modification
    const editPatientModal = document.getElementById('edit-patient-modal');
    const closeEditModal = document.getElementById('close-edit-modal');
    const cancelEditBtn = document.getElementById('cancel-edit-btn');
    const editPatientForm = document.getElementById('edit-patient-form');

    const closeEditPatientModal = () => editPatientModal.classList.add('hidden');
    closeEditModal.addEventListener('click', closeEditPatientModal);
    cancelEditBtn.addEventListener('click', closeEditPatientModal);

    // Ouvrir le modal de modification et remplir les champs
    document.querySelectorAll('.edit-patient-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            
            const patientId = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const gender = button.getAttribute('data-gender');
            const birthDay = button.getAttribute('data-birth-day');
            const cin = button.getAttribute('data-cin');
            const phone = button.getAttribute('data-phone');
            const email = button.getAttribute('data-email');
            const mutuelle = button.getAttribute('data-mutuelle');
            const allergies = button.getAttribute('data-allergies');
            const chronic = button.getAttribute('data-chronic');
            const notes = button.getAttribute('data-notes');
            
            editPatientForm.action = "{{ route('patients.update', ['id' => ':id']) }}".replace(':id', patientId);
            
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_gender').value = gender;
            document.getElementById('edit_birth_day').value = birthDay;
            document.getElementById('edit_CIN').value = cin;
            document.getElementById('edit_phone_num').value = phone;
            document.getElementById('edit_email').value = email || '';
            document.getElementById('edit_mutuelle').value = mutuelle || '';
            document.getElementById('edit_allergies').value = allergies || '';
            document.getElementById('edit_chronic_conditions').value = chronic || '';
            document.getElementById('edit_notes').value = notes || '';
            
            editPatientModal.classList.remove('hidden');
        });
    });

    // Fonction pour afficher les notifications toast
    function showToast(message, isSuccess = true) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.style.backgroundColor = isSuccess ? '#4CAF50' : '#f44336';
        toast.style.display = 'block';
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.style.display = 'none';
                toast.style.opacity = '1';
            }, 300);
        }, 3000);
    }

    // Gestion de l'archivage des patients
    document.querySelectorAll('.archive-patient-btn').forEach(button => {
        button.addEventListener('click', async (e) => {
            e.stopPropagation();
            
            const patientId = button.getAttribute('data-id');
            const isArchived = button.getAttribute('data-archived') === '1';
            
            const confirmMessage = isArchived 
                ? 'Voulez-vous vraiment désarchiver ce patient ?' 
                : 'Voulez-vous vraiment archiver ce patient ?';
            
            if (!confirm(confirmMessage)) {
                return;
            }
            
            // Vérifie si nous sommes sur la page des archives
            const showArchived = new URLSearchParams(window.location.search).has('archived');
            
            try {
                const response = await fetch(`/patients/${patientId}/archive`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        archived: !isArchived,
                        fromArchived: showArchived // Transmets l'information si nous étions sur la page d'archives
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast(data.message);
                    
                    // Rediriger vers la page appropriée après un court délai
                    setTimeout(() => {
                        window.location.href = data.redirect || window.location.href;
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Erreur lors de la mise à jour');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showToast(error.message || 'Une erreur est survenue', false);
            }
        });
    });

    // Basculer entre patients archivés et non archivés
    document.getElementById('show-archived-btn').addEventListener('click', () => {
        const showArchived = new URLSearchParams(window.location.search).has('archived');
        window.location.href = showArchived 
            ? "{{ route('patients.index') }}" 
            : "{{ route('patients.index', ['archived' => true]) }}";
    });

    // Cacher le message de succès après 2 secondes
    setTimeout(function () {
        const message = document.getElementById('successMessage');
        if (message) {
            message.style.transition = 'opacity 0.2s ease';
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 200);
        }
    }, 2000);
</script>

</body>
</html>