<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Propre</title>

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/appointment-actions.js') }}"></script>
    <style>
        #addVisitBtn {
            border: 2px solid rgb(66, 41, 206) !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 9999 !important;
        }

        /* Avatar zoom effect */
        .zoomable-avatar {
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .zoomable-avatar.zoomed {
            transform: scale(1.5);
            z-index: 10000;
        }

        /* Nouveaux styles pour le zoom */
        #zoomedAvatar {
            transition: transform 0.3s ease;
        }
        
        #avatarZoomContainer:hover #zoomedAvatar {
            transform: scale(1.05);
        }
        
        /* Style pour les lignes filtrées */
        tr.hidden {
            display: none;
        }

        /* Styles pour les lignes du tableau historique */
        .clickable-row {
            transition: background-color 0.3s ease;
        }

        .clickable-row:hover {
            background-color: #d4f5ba !important;
        }

        .clickable-row.selected {
            background-color: #0d9151 !important;
            color: rgb(255, 255, 255) !important;
        }

        .clickable-row.selected td {
            color: rgb(255, 255, 255) !important;
        }
input[type="checkbox"] {
    accent-color: #278e4d;
}
        /* Style pour la checkbox dans une ligne sélectionnée */
        .clickable-row.selected input[type="checkbox"] {
            accent-color: rgb(255, 255, 255);
        }
    </style>
</head>
<body class="flex bg-gray-100 m-0 p-0">

    <!-- Sidebar -->
    <x-sidebar class="w-64 fixed left-0 top-0 h-full bg-gray-200 shadow-md" />

    <div class="flex-1 flex flex-col pl-64 bg-blue-100 overflow-y-auto">
        <x-header />

        @if(session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-4 mt-16" role="alert">
            <strong class="font-bold">Succès !</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <div class="flex items-center justify-between p-4 pt-16">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center space-x-3">
                @if($patient->gender === 'Female')
                    <img src="{{ asset('images/female-avatar.png') }}" alt="Avatar Femme" class="w-10 h-10 rounded-full object-cover border border-gray-300 zoomable-avatar">
                @else
                    <img src="{{ asset('images/male-avatar.png') }}" alt="Avatar Homme" class="w-10 h-10 rounded-full object-cover border border-gray-300 zoomable-avatar">
                @endif
                <span> {{ $patient->name }} </span>
            </h1>

            <div class="flex space-x-4">
                <button id="editInfoBtn"  title="Modifier les informations personnelles du patient" class=" px-4 py-2 bg-blue-400 text-white rounded-2xl gap-2 hover:bg-blue-600">
                    <i class="fas fa-edit mr-2 "></i> Modifier Infos
                </button>
                <button class="hidden px-4 py-2 bg-blue-400 text-white rounded-2xl hover:bg-blue-600">
                    <i class="fas fa-calendar-alt mr-2"></i> Planifiez Rendez-vous
                </button>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 p-4 overflow-auto">
            <div class="bg-white rounded-2xl shadow-md p-6 space-y-2 max-h-[600px] overflow-y-auto overflow-x-auto">
                <h3 class="text-lg font-semibold mb-3 p-2.5 border-b border-gray-300 text-gray-700 flex items-center space-x-2">
                    @if($patient->gender === 'Female')
                        <img src="{{ asset('images/female-avatar.png') }}" alt="Avatar Femme" class="w-6 h-6 rounded-full object-cover border border-gray-300 zoomable-avatar">
                    @else
                        <img src="{{ asset('images/male-avatar.png') }}" alt="Avatar Homme" class="w-6 h-6 rounded-full object-cover border border-gray-300 zoomable-avatar">
                    @endif
                    Informations Personnelles
                </h3>

                <x-patient-info
                    :name="$patient->name"
                    :age="today()->diffInYears($patient->birth_day)"
                    :sex="$patient->gender"
                    :phone="$patient->phone_num"
                    :cin="$patient->CIN"
                    :mutuelle="$patient->mutuelle ?? 'Aucune'"
                    :allergies="$patient->allergies ?? 'Aucune'"
                    :chronic="$patient->chronic_conditions ?? 'Aucune'"
                    :email="$patient->email??'Aucune'"
                    :notes="$patient->notes??'Aucune'"                 
                />
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 space-y-2 max-h-[400px] overflow-y-auto overflow-x-auto">
                <h3 class="text-lg font-semibold mb-3 p-2.5 border-b border-gray-300 text-blue-700">
                    <i class="fas fa-calendar-check mr-2"></i> Dernière Consultation
                </h3>
                @if($lastAppointment)
                    <x-last-appo
                        :date="$lastAppointment->appointment_date ?? ''"
                        :type="$lastAppointment->type ?? ''"
                        :diagnostic="$lastAppointment->diagnostic ?? ''"
                    />
                @else
                    <p>Pas encore</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 p-4">
            <div class="bg-white rounded-2xl shadow-md p-6 space-y-2 overflow-auto">
                <div class="flex items-center justify-between mb-3 p-2.5 border-b border-gray-300">
                    <div class="w-1/3">
<input
  type="text"
  id="searchVisit"
  placeholder="Rechercher (JJ-MM-AAAA)..."
  class="px-4 py-2 rounded-lg border border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100 w-full text-sm"
/>
                    </div>
                
                    <h3 class="text-lg font-semibold text-green-700 text-center flex-grow text-center">
                        <i class="fas fa-file-alt mr-2"></i> Historique des Visites
                    </h3>
                </div>

                <table class="w-full border-separate border-spacing-0 shadow-md rounded-[12px] overflow-hidden">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border-b">Date</th>
                            <th class="px-4 py-2 border-b">Type</th>
                            <th class="px-4 py-2 border-b">Coût</th>
                            <th class="px-4 py-2 border-b">Mutuelle</th>
                          
                        </tr>
                    </thead>
                    <tbody id="visitTableBody">
                        @foreach($appointementsHistory as $appo)
                            <x-visit-history-element
                                :date="$appo->appointment_date ?? ''"
                                :type="$appo->type ?? ''"
                                :payement="$appo->payement ?? ''"
                                :appointmentId="$appo->ID_RV"
                                :mutuelle="$appo->mutuelle ?? false"
                            />
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   <!-- Modal pour éditer les informations du patient -->
   <div id="editPatientModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden transition-all duration-300 ease-in-out z-50">
    <div class="bg-white w-full max-w-2xl p-8 rounded-xl shadow-2xl transform transition-all duration-300 ease-in-out max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold ">Modifier le Patient</h2>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <form action="{{ route('patients.updatee', $patient->ID_patient) }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom et Prénom</label>
                    <input type="text" id="name" name="name" value="{{ $patient->name }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="birth_day" class="block text-sm font-medium text-gray-700">Date de naissance</label>
                    <input type="date" id="birth_day" name="birth_day" value="{{ $patient->birth_day }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Genre</label>
                    <select id="gender" name="gender" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="Male" {{ $patient->gender === 'Male' ? 'selected' : '' }}>Homme</option>
                        <option value="Female" {{ $patient->gender === 'Female' ? 'selected' : '' }}>Femme</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="phone_num" class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="text" id="phone_num" name="phone_num" value="{{ $patient->phone_num }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="CIN" class="block text-sm font-medium text-gray-700">CIN</label>
                    <input type="text" id="CIN" name="CIN" value="{{ $patient->CIN }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ $patient->email }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="mutuelle" class="block text-sm font-medium text-gray-700">Mutuelle</label>
                    <select id="mutuelle" name="mutuelle" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Aucune</option>
                        <option value="CNSS" {{ $patient->mutuelle === 'CNSS' ? 'selected' : '' }}>CNSS</option>
                        <option value="CNOPS" {{ $patient->mutuelle === 'CNOPS' ? 'selected' : '' }}>CNOPS</option>
                    </select>
                </div>

                <div class="mb-4 col-span-2">
                    <label for="allergies" class="block text-sm font-medium text-gray-700">Allergies</label>
                    <textarea id="allergies" name="allergies" rows="2" 
                             class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $patient->allergies }}</textarea>
                </div>

                <div class="mb-4 col-span-2">
                    <label for="chronic_conditions" class="block text-sm font-medium text-gray-700">Maladies chroniques</label>
                    <textarea id="chronic_conditions" name="chronic_conditions" rows="2" 
                             class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $patient->chronic_conditions }}</textarea>
                </div>

                <div class="mb-4 col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea id="notes" name="notes" rows="3" 
                             class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $patient->notes }}</textarea>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" id="cancelEditBtn" class="mr-2 px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">Annuler</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

   <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du modal
            const editInfoBtn = document.getElementById('editInfoBtn');
            const editPatientModal = document.getElementById('editPatientModal');
            const closeModal = document.getElementById('closeModal');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
    
            editInfoBtn?.addEventListener('click', () => {
                editPatientModal?.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
    
            closeModal?.addEventListener('click', () => {
                editPatientModal?.classList.add('hidden');
                document.body.style.overflow = 'auto';
            });
    
            cancelEditBtn?.addEventListener('click', () => {
                editPatientModal?.classList.add('hidden');
                document.body.style.overflow = 'auto';
            });
    
            window.addEventListener('click', function(e) {
                if (e.target === editPatientModal) {
                    editPatientModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });
    
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    successMessage.style.transition = 'opacity 1s';
                    setTimeout(() => successMessage.remove(), 1000);
                }, 5000);
            }
    
            // Zoom image fullscreen
            const zoomContainer = document.getElementById('avatarZoomContainer');
            const zoomedImg = document.getElementById('zoomedAvatar');
    
            document.querySelectorAll('.zoomable-avatar').forEach(avatar => {
                avatar.addEventListener('click', () => {
                    zoomedImg.src = avatar.src;
                    zoomContainer.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            });
    
            zoomContainer.addEventListener('click', () => {
                zoomContainer.classList.add('hidden');
                zoomedImg.src = "";
                document.body.style.overflow = 'auto';
            });
            
            // Fonctionnalité de recherche par date
            const searchInput = document.getElementById('searchVisit');
            
            searchInput.addEventListener('input', function() {
                const searchValue = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('#visitTableBody tr');
                
                rows.forEach(row => {
                    const dateCell = row.querySelector('td:first-child');
                    if (dateCell) {
                        const dateText = dateCell.textContent.toLowerCase();
                        
                        // Vérifie si le texte de la date contient la valeur recherchée
                        if (dateText.includes(searchValue)) {
                            row.classList.remove('hidden');
                        } else {
                            row.classList.add('hidden');
                        }
                    }
                });
            });

            // Gestion de la sélection des lignes du tableau
            document.querySelectorAll('.clickable-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    // Vérifier si le clic provient de la checkbox ou du formulaire
                    if (e.target.type === 'checkbox' || e.target.tagName === 'FORM') {
                        return; // Ne pas gérer la sélection si c'est un clic sur la checkbox
                    }
                    
                    // Retirer la sélection de toutes les autres lignes
                    document.querySelectorAll('.clickable-row').forEach(otherRow => {
                        otherRow.classList.remove('selected');
                    });
                    
                    // Ajouter la sélection à la ligne cliquée
                    this.classList.add('selected');
                    
                    // Redirection vers les détails du rendez-vous
                    const appointmentId = this.dataset.appointmentId;
                    if (appointmentId) {
                        // Petite pause pour voir l'effet de sélection avant la redirection
                        setTimeout(() => {
                            window.location.href = `/appointments/${appointmentId}/details`;
                        }, 200);
                    }
                });
            });
        });
    </script>
    
<div id="avatarZoomContainer" class="fixed inset-0 bg-black bg-opacity-70 hidden flex items-center justify-center z-[9999]">
    <img id="zoomedAvatar" src="" alt="Avatar Zoomé" 
         class="rounded-full shadow-2xl border-4 border-white object-cover"
         style="width: 8cm; height: 8cm;">
</div>
</body>
</html>