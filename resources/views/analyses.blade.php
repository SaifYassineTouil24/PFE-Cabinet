<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Propre - Analyses</title>
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
            background-color: #ef4444;
        }

        .delete-btn:hover {
            background-color: #dc2626;
            transform: scale(1.05);
        }
        
        .archive-btn {
            background-color: #7c7a75;
        }
        
        .archive-btn:hover {
            background-color: #ff6e35;
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
            background-color: #5d44ff;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
            white-space: nowrap;
            margin-left: auto;
        }

        .add-btn:hover {
            background-color: #2a2892;
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
            background-color: #ef4444;
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
            background-color: #ef4444 !important;
            color: rgb(255, 255, 255) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem !important;
            transition: all 0.2s !important;
        }

        .delete-confirm-btn:hover {
            background-color: #dc2626 !important;
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

<x-sidebar class="w-64 fixed left-0 top-0 h-full bg-white shadow-sm" />

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

<div class="flex-1 flex flex-col pl-64">
    <x-header />

    <main class="flex-1 overflow-auto p-6">
        <div class="main-content">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-2xl font-semibold text-gray-800">
                    <span class="text-blue-700 border-b border-gray-600 italic">Liste</span>
                    <span class="text-gray-600">des Analyses</span>
                </h1>

                <div class="flex flex-col w-full md:flex-row md:items-center gap-4">
                    <!-- Onglets Archive / Non-archivés -->
                    <div class="flex gap-2 mr-4">
                        <a href="{{ route('analyses.index') }}" class="archive-chip {{ !isset($showArchived) || !$showArchived ? 'active' : '' }}">
                            <i class="fas fa-flask mr-1"></i> Actifs
                        </a>
                        <a href="{{ route('analyses.index', ['archived' => 1]) }}" class="archive-chip {{ isset($showArchived) && $showArchived ? 'active' : '' }}">
                            <i class="fas fa-archive mr-1"></i> Archivés
                        </a>
                    </div>
                    
                    <div class="search-container mr-auto">
                        <div class="search-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" id="search-input" placeholder="Rechercher une analyse..." class="search-input">
                    </div>

                    <button id="add-analysis-btn" class="add-btn text-white flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>
                        <span>Ajouter Analyse</span>
                    </button>
                </div>
            </div>

            <div class="table-container">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase">Type d'Analyse</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase">Département</th>
                        @if(isset($showArchived) && $showArchived)
                            <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase">Statut</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($analyses as $analysis)
                        <tr class="table-row hover:bg-gray-50 transition-colors cursor-pointer"
                            data-id="{{ $analysis->ID_Analyse }}"
                            data-type="{{ $analysis->type_analyse }}"
                            data-department="{{ $analysis->departement }}"
                            data-archived="{{ $analysis->archived }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $analysis->ID_Analyse }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $analysis->type_analyse }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $analysis->departement }}</td>
                            @if(isset($showArchived) && $showArchived)
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($analysis->archived == 1)
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
                                    @if($analysis->archived == 0)
                                        <button class="action-btn edit-btn edit-analysis-btn"
                                                data-id="{{ $analysis->ID_Analyse }}"
                                                data-type="{{ $analysis->type_analyse }}"
                                                data-department="{{ $analysis->departement }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn archive-btn archive-analysis-btn"
                                                data-id="{{ $analysis->ID_Analyse }}"
                                                data-type="{{ $analysis->type_analyse }}">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    @else
                                        <button class="action-btn restore-btn restore-analysis-btn"
                                                data-id="{{ $analysis->ID_Analyse }}"
                                                data-type="{{ $analysis->type_analyse }}">
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
                                    <i class="fas fa-flask text-3xl text-blue-300 mb-2"></i>
                                    <p>Aucune analyse {{ isset($showArchived) && $showArchived ? 'archivée' : '' }} trouvée</p>
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

<!-- Analysis Modal -->
<div id="analysis-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
    <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-xl modal-content modal-animation">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800" id="modal-title">Ajouter une Analyse</h2>
            <button class="text-gray-400 hover:text-gray-600 transition close-modal">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form method="POST" id="analysis-form" action="{{ route('analyses.store') }}">
            @csrf
            <input type="hidden" name="analysis_id" id="analysis_id">
            <div class="space-y-4">
                <div>
                    <label for="type_analyse" class="block text-sm font-medium text-gray-700 mb-1">Type d'Analyse*</label>
                    <input type="text" name="type_analyse" id="type_analyse" required
                           class="input-field @error('type_analyse') input-error @enderror"
                           placeholder="Ex: Hématologie, Biochimie">
                    @error('type_analyse')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="departement" class="block text-sm font-medium text-gray-700 mb-1">Département*</label>
                    <select name="departement" id="departement" required
                            class="input-field @error('departement') input-error @enderror">
                        <option value="">Sélectionnez un département</option>
                        <option value="Laboratoire">Laboratoire</option>
                        <option value="Radiologie">Radiologie</option>
                        <option value="Microbiologie">Microbiologie</option>
                    </select>
                    @error('departement')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" class="cancel-btn px-4 py-2">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
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
            <p class="mt-2 text-gray-600">Êtes-vous sûr de vouloir archiver <span id="analysis-type-to-archive" class="font-bold text-gray-800"></span> ?</p>
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
            <p class="mt-2 text-gray-600">Êtes-vous sûr de vouloir restaurer <span id="analysis-type-to-restore" class="font-bold text-gray-800"></span> ?</p>
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

<!-- Details Modal -->
<div id="details-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
    <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-xl modal-content modal-animation">
        <div class="mb-4 text-center">
            <h3 class="text-lg font-bold text-blue-600" id="detail-type"></h3>
            <p class="text-sm text-gray-500">ID: <span id="detail-id" class="font-medium text-gray-700"></span></p>
            <p class="mt-2 text-gray-600 font-medium" id="detail-department"></p>
        </div>
        <div class="overflow-hidden border border-gray-200 rounded-lg shadow-sm mt-4">
            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="bg-white divide-y divide-gray-200">
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
                <i class="fas fa-times mr-2"></i> Fermer
            </button>
        </div>
    </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
    // Modal Elements
    const analysisModal = document.getElementById('analysis-modal');
    const archiveModal = document.getElementById('archive-confirmation-modal');
    const restoreModal = document.getElementById('restore-confirmation-modal');
    const detailsModal = document.getElementById('details-modal');

    // Buttons
    const addBtn = document.getElementById('add-analysis-btn');
    const closeModals = document.querySelectorAll('.close-modal, .cancel-btn, #close-details-btn');
    const editBtns = document.querySelectorAll('.edit-analysis-btn');
    const archiveBtns = document.querySelectorAll('.archive-analysis-btn');
    const restoreBtns = document.querySelectorAll('.restore-analysis-btn');

    // Forms
    const analysisForm = document.getElementById('analysis-form');
    const archiveForm = document.getElementById('archive-form');
    const restoreForm = document.getElementById('restore-form');

    // Add New Analysis
    addBtn.addEventListener('click', () => {
        resetForm();
        analysisForm.action = "/analyses";
        document.getElementById('modal-title').textContent = "Ajouter une Analyse";
        analysisModal.classList.remove('hidden');
    });

    // Edit Analysis
    editBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();

            const id = btn.dataset.id;
            const type = btn.dataset.type;
            const department = btn.dataset.department;

            document.getElementById('analysis_id').value = id;
            document.getElementById('type_analyse').value = type;
            document.getElementById('departement').value = department;

            analysisForm.action = `/analyses/${id}`;
            document.getElementById('modal-title').textContent = "Modifier l'Analyse";

            // Add method spoofing for PUT
            if (!document.getElementById('method-put')) {
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PUT';
                method.id = 'method-put';
                analysisForm.appendChild(method);
            }

            analysisModal.classList.remove('hidden');
        });
    });

    // Archive Analysis
    archiveBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();

            const id = btn.dataset.id;
            const type = btn.dataset.type;

            document.getElementById('analysis-type-to-archive').textContent = type;
            archiveForm.action = `/analyses/${id}/archive`;
            archiveModal.classList.remove('hidden');
        });
    });

    // Restore Analysis
    restoreBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();

            const id = btn.dataset.id;
            const type = btn.dataset.type;

            document.getElementById('analysis-type-to-restore').textContent = type;
            restoreForm.action = `/analyses/${id}/restore`;
            restoreModal.classList.remove('hidden');
        });
    });

    // Row Click for Details
    document.querySelectorAll('.table-row').forEach(row => {
        row.addEventListener('click', (e) => {
            if (e.target.closest('button')) return;

            const id = row.dataset.id;
            const type = row.dataset.type;
            const department = row.dataset.department;
            const archived = row.dataset.archived;

            document.getElementById('detail-id').textContent = id;
            document.getElementById('detail-type').textContent = type;
            document.getElementById('detail-department').textContent = department;
            
            const statusElement = document.getElementById('detail-status');
            if (archived === '1') {
                statusElement.textContent = 'Archivé';
                statusElement.className = 'status-pill status-archived';
            } else {
                statusElement.textContent = 'Actif';
                statusElement.className = 'status-pill status-active';
            }
            
            detailsModal.classList.remove('hidden');
        });
    });

    // Close Modals
    closeModals.forEach(btn => {
        btn.addEventListener('click', () => {
            analysisModal.classList.add('hidden');
            archiveModal.classList.add('hidden');
            restoreModal.classList.add('hidden');
            detailsModal.classList.add('hidden');
        });
    });

    // Cancel archive
    document.getElementById('cancel-archive-btn').addEventListener('click', () => {
        archiveModal.classList.add('hidden');
    });

    // Cancel restore
    document.getElementById('cancel-restore-btn').addEventListener('click', () => {
        restoreModal.classList.add('hidden');
    });

    // Search Functionality
    document.getElementById('search-input').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.table-row').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });

    function resetForm() {
        analysisForm.reset();
        const method = document.getElementById('method-put');
        if (method) method.remove();
        document.querySelectorAll('.error-message').forEach(el => el.remove());
        document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
    }

    // Auto-close notifications
    setTimeout(() => {
        document.querySelectorAll('#message-container > div').forEach(notification => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        });
    }, 5000);
});
</script>

</body>
</html>
