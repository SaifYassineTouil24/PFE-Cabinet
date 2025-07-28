<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Propre - Médicaments</title>
    @vite('resources/css/app.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        /* Styles optimisés */
        body {
            background-color: #f8fafc;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 6px;
            font-size: 14px;
            color: white;
            transition: all 0.2s ease;
            margin-right: 6px;
        }
        
        .edit-btn {
            background-color: #3b82f6;
        }
        
        .edit-btn:hover {
            background-color: #2563eb;
            transform: scale(1.05);
        }
        
        .delete-btn {
            background-color: #8e8686;
        }
        
        .delete-btn:hover {
            background-color: #746c6c;
            transform: scale(1.05);
        }
        
        .archive-btn {
            background-color: #7c7a75;
        }
        
        .archive-btn:hover {
            background-color: #535151;
            transform: scale(1.05);
        }
        
        .restore-btn {
            background-color: #10b981;
        }
        
        .restore-btn:hover {
            background-color: #059669;
            transform: scale(1.05);
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
            background-color:  #006de1;
            transform: translateY(-1px);
        }
        
        .search-container {
            display: flex;
            justify-content: flex-start;
            position: relative;
            width: 100%;
            max-width: 350px;
        }
        
        .search-input {
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 8px 12px 8px 36px;
            font-size: 14px;
            width: 100%;
            border: 1px solid #e2e8f0;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        
        .table-row:hover {
            background-color: #f8fafc;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modal-animation {
            animation: fadeIn 0.3s ease-out;
        }
        
        .notification {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            opacity: 1;
            transition: all 0.5s ease;
            max-width: 300px;
        }
        
        .notification-success {
            background-color: rgb(39, 88, 21);
            color: white;
        }
        
        .notification-danger {
            background-color: #857474;
            color: white;
        }
        
        .modal-content {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: none;
        }
        
        .input-field {
            transition: all 0.2s ease;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            width: 100%;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .main-content {
            border: none;
            box-shadow: none;
            background-color: transparent;
            padding: 0;
        }
        
        .table-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        #delete-confirmation-modal {
            z-index: 9999;
        }
        
        .table-row td:last-child {
            white-space: nowrap;
        }
        
        @media (max-width: 768px) {
            .add-btn {
                margin-left: 0;
                width: 100%;
            }
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .input-error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
        
        .delete-confirm-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            background-color: #918f8f !important;
            color: rgb(255, 255, 255) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem !important;
            transition: all 0.2s !important;
        }
        
        .delete-confirm-btn:hover {
            background-color: #8b8b8b !important;
        }
        
        .cancel-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border: 1px solid #e2e8f0 !important;
            color: rgb(91, 107, 128) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem !important;
            transition: all 0.2s !important;
        }
         
        .cancel-btn:hover {
            background-color: #f3f4f6 !important;
        }
        
        .archive-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            background-color: #f3f4f6;
            color: #4b5563;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .archive-chip.active {
            background-color: #e0f2fe;
            color: #0369a1;
        }
        
        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .status-active {
            background-color: #dcfce7;
            color: #16a34a;
        }
        
        .status-archived {
            background-color: #fee2e2;
            color: #887777;
        }
    </style>
</head>
<body class="font-sans antialiased">

<!-- Sidebar -->
<x-sidebar class="w-64 fixed left-0 top-0 h-full bg-white shadow-sm" />

<!-- Container pour les notifications -->
<div id="message-container" class="fixed top-4 right-4 z-50">
    @if(session('success'))
        <div class="notification notification-success">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="notification notification-danger">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
</div>

<!-- Main content -->
<div class="flex-1 flex flex-col pl-64">
    <!-- Header -->
    <x-header />

    <!-- Page Content -->
    <main class="flex-1 overflow-auto p-6">
        <div class="main-content">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-2xl font-semibold text-gray-800">
                    <span class="text-blue-700 border-b border-gray-600" style="font-style: italic;">Liste </span> 
                    <span class=" #7d7997">des Médicaments</span>
                </h1>
                
                <div class="flex flex-col w-full md:flex-row md:items-center gap-4">
                    <!-- Onglets Archive / Non-archivés -->
                    <div class="flex gap-2 mr-4">
                        <a href="{{ route('medicaments.index') }}" class="archive-chip {{ !isset($showArchived) || !$showArchived ? 'active' : '' }}">
                            <i class="fas fa-pills mr-1"></i> Actifs
                        </a>
                        <a href="{{ route('medicaments.index', ['archived' => 1]) }}" class="archive-chip {{ isset($showArchived) && $showArchived ? 'active' : '' }}">
                            <i class="fas fa-archive mr-1"></i> Archivés
                        </a>
                    </div>
                    
                    <!-- Barre de recherche -->
                    <div class="search-container mr-auto">
                        <div class="search-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" id="search-input" placeholder="Rechercher un médicament..." class="search-input" />
                    </div>
                    
                    <!-- Bouton Ajouter -->
                    <button id="add-medication-btn" class="add-btn text-white flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>
                        <span>Ajouter Médicament</span>
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-40">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Prix (DH)</th>
                        @if(isset($showArchived) && $showArchived)
                            <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Statut</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($medicaments as $med)
                        <tr class="table-row hover:bg-gray-40 transition-colors cursor-pointer"
                            data-id="{{ $med->ID_Medicament }}"
                            data-name="{{ $med->name }}"
                            data-price="{{ number_format($med->price, 2) }}"
                            data-description="{{ $med->description ?? 'Non spécifié' }}"
                            data-dosage="{{ $med->dosage ?? 'Non spécifié' }}" 
                            data-composition="{{ $med->composition ?? 'Non spécifié' }}"
                            data-archived="{{ $med->archived }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $med->ID_Medicament }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $med->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ number_format($med->price, 2) }}</td>
                            @if(isset($showArchived) && $showArchived)
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($med->archived == 1)
                                        <span class="status-pill status-archived">
                                            <i class="fas fa-archive mr-1"></i> Archivé
                                        </span>
                                    @else
                                        <span class="status-pill status-active">
                                            <i class="fas fa-check-circle mr-1"></i> Actif
                                        </span>
                                    @endif
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <div class="flex space-x-1">
                                    @if($med->archived == 0)
                                        <button class="action-btn edit-btn edit-medication-btn"
                                                data-id="{{ $med->ID_Medicament }}"
                                                data-name="{{ $med->name }}"
                                                data-price="{{ $med->price }}"
                                                data-description="{{ $med->description ?? '' }}"
                                                data-dosage="{{ $med->dosage ?? '' }}" 
                                                data-composition="{{ $med->composition ?? '' }}"
                                                aria-label="Modifier"
                                                title="Modifier Médicament">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <button class="action-btn archive-btn archive-medication-btn"
                                                data-id="{{ $med->ID_Medicament }}"
                                                data-name="{{ $med->name }}"
                                                aria-label="Archiver"
                                                title="Archiver Médicament">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    @else
                                        <button class="action-btn restore-btn restore-medication-btn"
                                                data-id="{{ $med->ID_Medicament }}"
                                                data-name="{{ $med->name }}"
                                                aria-label="Restaurer"
                                                title="Restaurer Médicament">
                                            <i class="fas fa-undo-alt"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ isset($showArchived) && $showArchived ? '5' : '4' }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center py-6">
                                    <i class="fas fa-pills text-3xl text-blue-300 mb-2"></i>
                                    <p>Aucun médicament {{ isset($showArchived) && $showArchived ? 'archivé' : '' }} trouvé</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<!-- Modal pour Ajouter/Modifier un Médicament -->
<div id="medication-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
    <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-xl modal-content modal-animation">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800" id="modal-title">Ajouter un Médicament</h2>
            <button id="close-modal" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('medicaments.store') }}" id="medication-form">
            @csrf
            <input type="hidden" name="medication_id" id="medication_id">
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom*</label>
                    <input type="text" name="name" id="name" placeholder="Nom du médicament" required 
                           class="input-field @error('name') input-error @enderror"
                           value="{{ old('name') }}">
                    @error('name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3" placeholder="Description du médicament"
                              class="input-field @error('description') input-error @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Prix*</label>
                    <input type="number" name="price" id="price" placeholder="Prix en dirhams" step="0.01" required 
                           class="input-field @error('price') input-error @enderror"
                           value="{{ old('price') }}">
                    @error('price')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="dosage" class="block text-sm font-medium text-gray-700 mb-1">Dosage</label>
                    <input type="text" name="dosage" id="dosage" placeholder="Ex: 500mg, 10mg/ml" 
                           class="input-field @error('dosage') input-error @enderror"
                           value="{{ old('dosage') }}">
                    @error('dosage')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="composition" class="block text-sm font-medium text-gray-700 mb-1">Composition</label>
                    <textarea name="composition" id="composition" rows="2" placeholder="Principes actifs et excipients"
                              class="input-field @error('composition') input-error @enderror">{{ old('composition') }}</textarea>
                    @error('composition')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" id="cancel-btn" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white  rounded-lg hover:#3936c5 transition flex items-center">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Confirmation pour l'archivage -->
<div id="archive-confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
    <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-xl modal-content modal-animation">
        <div class="flex flex-col items-center mb-6 text-center">
            <div class="mb-4 text-yellow-500 bg-yellow-50 p-4 rounded-full">
                <i class="fas fa-archive text-2xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Confirmer l'archivage</h2>
            <p class="mt-2 text-gray-600">Êtes-vous sûr de vouloir archiver <span id="medication-name-to-archive" class="font-bold text-gray-800"></span> ?</p>
        </div>
        <form method="POST" id="archive-form" action="">
            @csrf
            @method('PUT')
            <div class="flex justify-center space-x-4">
                <button type="button" id="cancel-archive-btn" class="cancel-btn">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-yellow-600 transition flex items-center justify-center">
                    <i class="fas fa-archive mr-2"></i> Archiver
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Confirmation pour la restauration -->
<div id="restore-confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
    <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-xl modal-content modal-animation">
        <div class="flex flex-col items-center mb-6 text-center">
            <div class="mb-4 text-green-500 bg-green-50 p-4 rounded-full">
                <i class="fas fa-undo-alt text-2xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Confirmer la restauration</h2>
            <p class="mt-2 text-gray-600">Êtes-vous sûr de vouloir restaurer <span id="medication-name-to-restore" class="font-bold text-gray-800"></span> ?</p>
        </div>
        <form method="POST" id="restore-form" action="">
            @csrf
            @method('PUT')
            <div class="flex justify-center space-x-4">
                <button type="button" id="cancel-restore-btn" class="cancel-btn">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition flex items-center justify-center">
                    <i class="fas fa-undo-alt mr-2"></i> Restaurer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Détails du Médicament -->
<div id="details-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
    <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-xl modal-content modal-animation">
        <div class="mb-4 text-center">
            <h3 class="text-lg font-bold text-blue-600" id="detail-name"></h3>
            <p class="text-sm text-gray-500">Code: <span id="detail-id" class="font-medium text-gray-700"></span></p>
            <p class="mt-2 text-gray-600 text-xl font-bold" id="detail-price"></p>
        </div>
        
        <div class="overflow-hidden border border-gray-200 rounded-lg shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-3 bg-gray-50 text-sm font-medium text-gray-700 w-1/3">Dosage</td>
                        <td class="px-4 py-3 text-sm text-gray-600" id="detail-dosage"></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 bg-gray-50 text-sm font-medium text-gray-700 w-1/3">Composition</td>
                        <td class="px-4 py-3 text-sm text-gray-600" id="detail-composition"></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 bg-gray-50 text-sm font-medium text-gray-700 w-1/3">Description</td>
                        <td class="px-4 py-3 text-sm text-gray-600" id="detail-description"></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 bg-gray-50 text-sm font-medium text-gray-700 w-1/3">Statut</td>
                        <td class="px-4 py-3 text-sm" id="detail-status-container">
                            <span id="detail-status" class="status-pill"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="flex justify-end mt-6">
            <button type="button" id="close-details-btn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center">
                <i class="fas fa-times mr-2"></i>
                Fermer
            </button>
        </div>
    </div>
</div>




<script>document.addEventListener('DOMContentLoaded', function() {
    // EDIT & ADD MODAL
    const modal = document.getElementById('medication-modal');
    const addBtn = document.getElementById('add-medication-btn');
    const closeModal = document.getElementById('close-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const modalTitle = document.getElementById('modal-title');
    const medicationForm = document.getElementById('medication-form');
    const medicationIdInput = document.getElementById('medication_id');
    
    // ARCHIVE MODAL
    const archiveModal = document.getElementById('archive-confirmation-modal');
    const cancelArchiveBtn = document.getElementById('cancel-archive-btn');
    const medicationNameToArchive = document.getElementById('medication-name-to-archive');
    const archiveForm = document.getElementById('archive-form');
    
    // RESTORE MODAL
    const restoreModal = document.getElementById('restore-confirmation-modal');
    const cancelRestoreBtn = document.getElementById('cancel-restore-btn');
    const medicationNameToRestore = document.getElementById('medication-name-to-restore');
    const restoreForm = document.getElementById('restore-form');
    
    // DETAILS MODAL
    const detailsModal = document.getElementById('details-modal');
    const closeDetailsBtn = document.getElementById('close-details-btn');
    
    // Show modal for adding medication
    if (addBtn) {
        addBtn.addEventListener('click', () => {
            resetForm();
            modalTitle.textContent = 'Ajouter un Médicament';
            medicationForm.action = "{{ route('medicaments.store') }}";
            medicationForm.method = "POST";
            modal.classList.remove('hidden');
        });
    }

    // Gérer les clics sur les boutons de modification
    document.querySelectorAll('.edit-medication-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            event.stopPropagation();
            
            resetForm();
            
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const price = button.getAttribute('data-price');
            const description = button.getAttribute('data-description');
            const dosage = button.getAttribute('data-dosage') || '';
            const composition = button.getAttribute('data-composition') || '';

            document.getElementById('medication_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('price').value = price;
            document.getElementById('description').value = description;
            document.getElementById('dosage').value = dosage;
            document.getElementById('composition').value = composition;    
            
            modalTitle.textContent = 'Modifier un Médicament';
            medicationForm.action = "{{ url('medicaments') }}/" + id;
            
            // Ajouter la méthode PUT pour la mise à jour
            if (!document.getElementById('method-put')) {
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                methodField.id = 'method-put';
                medicationForm.appendChild(methodField);
            }
            
            modal.classList.remove('hidden');
        });
    });

    // Barre de recherche - filtrage des médicaments
    const searchInput = document.getElementById('search-input');
    const tableRows = document.querySelectorAll('tbody tr');

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();

            tableRows.forEach(row => {
                const cellsText = row.textContent.toLowerCase();
                if (cellsText.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Gestionnaire d'événements pour les clics sur les lignes du tableau
    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('click', (event) => {
            // Ne pas déclencher si le clic est sur un bouton d'action
            if (event.target.closest('.action-btn') || event.target.closest('button')) {
                return;
            }
            
            // Récupérer les données de la ligne
            const id = row.getAttribute('data-id');
            const name = row.getAttribute('data-name');
            const price = row.getAttribute('data-price');
            const description = row.getAttribute('data-description');
            const dosage = row.getAttribute('data-dosage');
            const composition = row.getAttribute('data-composition');
            const archived = row.getAttribute('data-archived');
            
            // Remplir le modal avec les détails
            document.getElementById('detail-id').textContent = id;
            document.getElementById('detail-name').textContent = name;
            document.getElementById('detail-price').textContent = price + ' DH';
            document.getElementById('detail-description').textContent = description;
            document.getElementById('detail-dosage').textContent = dosage;
            document.getElementById('detail-composition').textContent = composition;
            
            // Mettre à jour le statut
            const statusElement = document.getElementById('detail-status');
            statusElement.className = archived == 1 ? 'status-pill status-archived' : 'status-pill status-active';
            statusElement.innerHTML = archived == 1 
                ? '<i class="fas fa-archive mr-1"></i> Archivé' 
                : '<i class="fas fa-check-circle mr-1"></i> Actif';
            
            // Afficher le modal
            detailsModal.classList.remove('hidden');
        });
    });

    // Gérer les clics sur les boutons d'archivage
    document.querySelectorAll('.archive-medication-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            event.stopPropagation();
            
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            
            medicationNameToArchive.textContent = name;
            archiveForm.action = "{{ url('medicaments/archive') }}/" + id;
            
            archiveModal.classList.remove('hidden');
        });
    });

    // Gérer les clics sur les boutons de restauration
    document.querySelectorAll('.restore-medication-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            event.stopPropagation();
            
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            
            medicationNameToRestore.textContent = name;
            restoreForm.action = "{{ url('medicaments/restore') }}/" + id;
            
            restoreModal.classList.remove('hidden');
        });
    });

    // Fermer les modals
    if (closeModal) {
        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    }
    
    if (cancelArchiveBtn) {
        cancelArchiveBtn.addEventListener('click', () => {
            archiveModal.classList.add('hidden');
        });
    }
    
    if (cancelRestoreBtn) {
        cancelRestoreBtn.addEventListener('click', () => {
            restoreModal.classList.add('hidden');
        });
    }
    
    // Fermer le modal de détails
    if (closeDetailsBtn) {
        closeDetailsBtn.addEventListener('click', () => {
            detailsModal.classList.add('hidden');
        });
    }
    
    // Réinitialiser le formulaire
    function resetForm() {
        medicationForm.reset();
        medicationIdInput.value = '';
        
        const methodField = document.getElementById('method-put');
        if (methodField) {
            methodField.remove();
        }
        
        // Supprimer les messages d'erreur
        document.querySelectorAll('.error-message').forEach(el => el.remove());
        document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
    }
    
    // Fermer automatiquement les notifications après 5 secondes
    setTimeout(() => {
        const notifications = document.querySelectorAll('#message-container > div');
        notifications.forEach(notification => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        });
    }, 5000);
});
</script>

</body>
</html>