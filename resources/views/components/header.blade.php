<!-- Header -->
<div class="flex justify-between items-center bg-blue-100 p-2 text-blue-600 border-b border-blue-300 shadow-md ">
    <!-- Bouton de retour -->
    <button onclick="goBack()" class="text-blue-600 hover:underline flex items-center gap-1">
    <i class="fas fa-arrow-left"></i>
    <span>Retour</span>
    </button>

    <!-- Left Section: Breadcrumb -->
    <div class="flex items-center gap-2">
        <i class="fas fa-compass text-blue-600 text-lg"></i>
        <div id="page-breadcrumb" class="text-sm font-medium">
            <!-- Breadcrumb dynamically updated -->
            <nav class="text-sm font-medium flex items-center gap-1 text-blue-600">
                <a href="{{ url('/') }}" class="hover:underline"></a>
            </nav>
            <a href="{{ url('/') }}" class="hover:underline">Accueil</a>
            <span>/</span>
            @php
                $segments = request()->segments();
            @endphp
            @foreach($segments as $key => $segment)
                @if($key + 1 < count($segments))
                    <a href="{{ url(implode('/', array_slice($segments, 0, $key + 1))) }}" class="hover:underline">
                        {{ ucfirst(str_replace('-', ' ', $segment)) }}
                    </a>
                    <span>/</span>
                @else
                    <span class="text-blue-500">{{ ucfirst(str_replace('-', ' ', $segment)) }}</span>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Right Section: Controls -->
    <div class="flex items-center gap-4">
        <!-- Live Clock -->
        <div class="flex items-center gap-3 bg-blue-600 backdrop-blur-md px-4 py-2 rounded-full shadow-md transition hover:shadow-lg h-8 w-32">
            <i class="fas fa-clock text-blue-100 text-lg"></i>
            <span id="live-clock" class="font-medium text-blue-100 text-sm w-[85px] text-center tracking-wide">
        --
    </span>
        </div>

        <script>
            function updateClock() {
                const clockElement = document.getElementById('live-clock');
                const now = new Date();
                clockElement.innerText = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }

            updateClock(); // Initial call to avoid delay
            setInterval(updateClock, 1000);
        </script>

        <!-- Action Buttons -->
        <button class="flex items-center gap-2 px-4 py-2 bg-[#4361ee] text-blue-100 rounded-full text-xs font-semibold shadow-md transition-all duration-200 hover:bg-[#3a0ca3] hover:shadow-lg hover:-translate-y-1">
            <i class="fas fa-user-plus text-sm"></i>
            <span>Ajouter Patient</span>
        </button>

        <button id="add-appointment-btn" class="flex items-center gap-2 px-4 py-2 bg-[#4361ee] text-blue-100 rounded-full  text-xs font-semibold shadow-md transition-all duration-200 hover:bg-[#3a0ca3] hover:shadow-lg hover:-translate-y-1">
            <i class="fas fa-calendar-plus text-sm"></i>
            <span>Ajouter Rendez-vous</span>
        </button>


    
        @auth
    <div class="flex items-center gap-2 bg-[#4361ee] text-blue-100 backdrop-blur-md p-2.5 rounded-xl shadow-md cursor-pointer transition hover:bg-[#3a0ca3] hover:shadow-lg">
        <div class="flex flex-col">
            <span class="font-semibold text-xs">{{ auth()->user()->name }}</span>
            <span class="text-[10px]">
                {{ auth()->user()->role === 'admin' ? 'Médecin' : 'Assistante' }}
            </span>        
        </div>
        <img src="{{asset('images/default-medcin.png') }}" alt="User Profile" class="w-8 h-8 rounded-full border-2 border-white object-cover">
    </div>
@endauth

    </div>

    <!-- Script pour le header -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sélectionner le bouton Ajouter Patient dans le header
        const addPatientHeaderBtn = document.querySelector('button:has(i.fas.fa-user-plus)');

        document.addEventListener('click', function(e) {
            // Clic sur le bouton "X"
            if (e.target.closest('#close-patient-modal')) {
                const modal = document.getElementById('patient-modal');
                if (modal) {
                    modal.classList.add('hidden');
                }
            }

            // Clic sur "Annuler"
            if (e.target.closest('#cancel-patient-btn')) {
                const modal = document.getElementById('patient-modal');
                if (modal) {
                    modal.classList.add('hidden');
                }
            }
        });
        
        if (addPatientHeaderBtn) {
            addPatientHeaderBtn.addEventListener('click', async function () {
                let patientModal = document.getElementById('patient-modal');

                if (patientModal) {
                    // Si le modal est déjà dans la page, on l'affiche
                    patientModal.classList.remove('hidden');
                } else {
                    console.log("⚠️ Modal #patient-modal non trouvé. Tentative de chargement via AJAX...");

                    try {
                        const response = await fetch('/patients/modal');
                        const html = await response.text();

                        // Créer un div temporaire pour parser le HTML
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html;

                        // Ajouter tous les éléments reçus dans le body
                        while (tempDiv.firstChild) {
                            document.body.appendChild(tempDiv.firstChild);
                        }

                        // Rechercher et afficher le nouveau modal injecté
                        patientModal = document.getElementById('patient-modal');
                        if (patientModal) {
                            patientModal.classList.remove('hidden');
                        } else {
                            alert("Erreur : le modal n'a pas pu être injecté.");
                        }
                    } catch (error) {
                        console.error("Erreur AJAX :", error);
                        alert("Impossible de charger le formulaire d'ajout du patient.");
                    }
                }
            });
        }

        // Sélectionner le bouton Ajouter Rendez-vous
        const addAppointmentBtn = document.getElementById('add-appointment-btn');
        const appointmentModal = document.getElementById('appointment-modal');
        const closeAppointmentModalBtn = document.getElementById('close-appointment-modal');
        const modalCalendarToggle = document.getElementById('modal-calendar-toggle');
        const modalCalendarContainer = document.getElementById('modal-calendar-container');
        
        // Ouvrir le modal de rendez-vous
        if (addAppointmentBtn) {
            addAppointmentBtn.addEventListener('click', function() {
                if (appointmentModal) {
                    appointmentModal.classList.remove('hidden');
                    // Initialiser le calendrier du modal
                    initModalCalendar();
                }
            });
        }
        
        // Fermer le modal de rendez-vous
        if (closeAppointmentModalBtn) {
            closeAppointmentModalBtn.addEventListener('click', function() {
                appointmentModal.classList.add('hidden');
            });
        }
        
        // Fermer le modal en cliquant sur l'arrière-plan
        if (appointmentModal) {
            appointmentModal.addEventListener('click', function(e) {
                if (e.target === appointmentModal) {
                    appointmentModal.classList.add('hidden');
                }
            });
        }
        
        // Gérer l'affichage du calendrier du modal
        if (modalCalendarToggle) {
            modalCalendarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                modalCalendarContainer.classList.toggle('hidden');
            });
        }
    });

    // Fonction pour initialiser le calendrier du modal avec coloration et indicateurs identiques à la page index
    function initModalCalendar() {
        const modalCalendarBody = document.getElementById('modal-calendar-body');
        const modalCurrentMonthEl = document.getElementById('modal-current-month');
        const modalPrevButton = document.getElementById('modal-prev-month');
        const modalNextButton = document.getElementById('modal-next-month');

        if (!modalCalendarBody || !modalCurrentMonthEl) return;

        let modalCurrentDate = new Date();
        let modalAppointmentsData = {}; // Pour stocker les données des rendez-vous

        // Fonction pour récupérer les rendez-vous du mois - corrigée
        function fetchModalAppointments(year, month, callback) {
            const monthStr = String(month + 1).padStart(2, '0');
            const yearMonth = `${year}-${monthStr}`;

            fetch(`/appointments-count/${yearMonth}`)
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {
                    modalAppointmentsData = data;
                    callback();
                })
                .catch(err => {
                    console.error('Erreur chargement rendez-vous modal:', err);
                    modalAppointmentsData = {};
                    callback();
                });
        }

        function renderModalCalendar(date) {
            const year = date.getFullYear();
            const month = date.getMonth();
            const today = new Date();

            // Update month display
            const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            modalCurrentMonthEl.textContent = `${monthNames[month]} ${year}`;

            // Clear previous calendar
            modalCalendarBody.innerHTML = '';

            // Add day names
            const dayNames = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];
            dayNames.forEach(day => {
                const dayEl = document.createElement('div');
                dayEl.textContent = day;
                dayEl.className = 'font-medium text-gray-500 text-xs p-1';
                modalCalendarBody.appendChild(dayEl);
            });

            // Get first day of month and number of days
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // Add empty cells for days before first of month
            for (let i = 0; i < firstDay; i++) {
                const emptyEl = document.createElement('div');
                modalCalendarBody.appendChild(emptyEl);
            }

            // Add days
            for (let day = 1; day <= daysInMonth; day++) {
                const dayStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const count = modalAppointmentsData[dayStr] || 0;

                const dayEl = document.createElement('div');
                dayEl.className = 'p-2 text-xs rounded hover:bg-blue-100 cursor-pointer text-center relative';
                dayEl.textContent = day;
                
                // Mettre en évidence le jour actuel
                if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
                    dayEl.classList.add('bg-blue-500', 'text-white', 'hover:bg-blue-600');
                }

                // Ajouter l'indicateur de rendez-vous avec les MÊMES couleurs que la page index
                if (count > 0) {
                    const indicator = document.createElement('span');
                    indicator.className = 'absolute bottom-0 right-0 w-2 h-2 rounded-full';

                    // Utilisation des MÊMES couleurs que dans index.blade.php
                    if (count <= 5) indicator.classList.add('bg-[#ffcc33]');
                    else if (count <= 10) indicator.classList.add('bg-[#ff9900]');
                    else if (count <= 15) indicator.classList.add('bg-[#ff6600]');
                    else indicator.classList.add('bg-red-700');

                    dayEl.appendChild(indicator);
                }
                
                // Événement de clic pour sélectionner une date
                dayEl.addEventListener('click', function() {
                    const dateField = document.getElementById('appointment-date');
                    const hiddenDateField = document.getElementById('appointment-date-hidden');
                    
                    if (dateField && hiddenDateField) {
                        // Format pour l'affichage (ex: "15 Mai 2024")
                        dateField.value = `${day} ${monthNames[month]} ${year}`;
                        
                        // Format pour le backend (ex: "2024-05-15")
                        const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        hiddenDateField.value = formattedDate;
                        
                        // Masquer le calendrier après sélection
                        const modalCalendarContainer = document.getElementById('modal-calendar-container');
                        if (modalCalendarContainer) {
                            modalCalendarContainer.classList.add('hidden');
                        }
                        
                        // Supprimer les erreurs de validation
                        const dateError = document.getElementById('date-error');
                        if (dateError) {
                            dateError.classList.add('hidden');
                        }
                        dateField.classList.remove('border-red-500');
                    }
                });

                modalCalendarBody.appendChild(dayEl);
            }
        }

        // Fonction pour mettre à jour le calendrier avec les données
        function updateModalCalendar() {
            const year = modalCurrentDate.getFullYear();
            const month = modalCurrentDate.getMonth();
            fetchModalAppointments(year, month, () => renderModalCalendar(modalCurrentDate));
        }

        // Rendu initial
        updateModalCalendar();

        // Event listeners pour les boutons précédent/suivant
        if (modalPrevButton) {
            modalPrevButton.addEventListener('click', (e) => {
                e.preventDefault();
                modalCurrentDate = new Date(modalCurrentDate.getFullYear(), modalCurrentDate.getMonth() - 1, 1);
                updateModalCalendar();
            });
        }

        if (modalNextButton) {
            modalNextButton.addEventListener('click', (e) => {
                e.preventDefault();
                modalCurrentDate = new Date(modalCurrentDate.getFullYear(), modalCurrentDate.getMonth() + 1, 1);
                updateModalCalendar();
            });
        }
    }

    // Fonction de retour
    function goBack() {
        if (window.history.length > 1) {
            window.history.back();
        }
    }
</script>
</div>

<!-- Modal pour ajouter un rendez-vous -->
<div id="appointment-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="bg-white rounded-lg shadow-lg p-6 z-10 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-blue-600">Ajouter un rendez-vous</h3>
            <button id="close-appointment-modal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="appointment-form" class="space-y-4">
            @csrf
            <!-- Champ patient avec autocomplétion amélioré -->
            <div>
                <label for="patient-search" class="block text-sm font-medium text-gray-700 mb-1">Nom du patient <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="text" id="patient-search" name="patient_name" 
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Commencez à taper un nom..." autocomplete="off" required>
                    <input type="hidden" id="selected-patient-id" name="patient_id" required>
                    <div id="patient-autocomplete" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto"></div>
                </div>
                <div id="search-error" class="text-red-500 text-xs mt-1 hidden">Veuillez sélectionner un patient existant</div>
                <p class="text-xs text-gray-500 mt-1">Un patient doit exister dans la base de données pour créer un rendez-vous</p>
            </div>

            <!-- Type de rendez-vous -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type de rendez-vous <span class="text-red-500">*</span></label>
                <div class="flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="appointment_type" value="Consultation" class="form-radio text-blue-600" checked>
                        <span class="ml-2">Consultation</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="appointment_type" value="Control" class="form-radio text-blue-600">
                        <span class="ml-2">Contrôle</span>
                    </label>
                </div>
            </div>

            <!-- Date du rendez-vous -->
            <div class="relative">
                <label for="appointment-date" class="block text-sm font-medium text-gray-700 mb-1">Date du rendez-vous <span class="text-red-500">*</span></label>
                <div class="flex items-center">
                    <input type="text" id="appointment-date" name="appointment_date" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Sélectionnez une date" readonly required>
                    <!-- Champ caché pour stocker la date au format YYYY-MM-DD -->
                    <input type="hidden" id="appointment-date-hidden" name="appointment_date_hidden">
                    <button type="button" id="modal-calendar-toggle" class="absolute right-2 text-blue-600">
                        <i class="fas fa-calendar"></i>
                    </button>
                </div>
                <div id="date-error" class="text-red-500 text-xs mt-1 hidden">Veuillez sélectionner une date</div>
                
                <!-- Calendrier inline du MODAL avec légende -->
                <div id="modal-calendar-container" class="absolute z-20 mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden w-full">
                    <div class="p-2">
                        <div class="flex justify-between items-center mb-2">
                            <button id="modal-prev-month" class="text-gray-600 hover:text-blue-600" type="button">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span id="modal-current-month" class="font-medium text-gray-700"></span>
                            <button id="modal-next-month" class="text-gray-600 hover:text-blue-600" type="button">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <div id="modal-calendar-body" class="grid grid-cols-7 gap-1 text-center">
                            <!-- Les jours seront ajoutés par JavaScript -->
                        </div>
                        <!-- Légende des couleurs - identique à la page index -->
                        <div class="mt-3 space-y-1 text-xs">
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-[#ffcc33] rounded-full"></span>
                                <span>1-5 RV</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-[#ff9900] rounded-full"></span>
                                <span>6-10 RV</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-[#ff6600] rounded-full"></span>
                                <span>11-15 RV</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-red-700 rounded-full"></span>
                                <span>>15 RV</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Note (optionnel) -->
            <div>
                <label for="patient-notes" class="block text-sm font-medium text-gray-700 mb-1">Note (optionnel)</label>
                <textarea id="patient-notes" name="patient_notes" rows="3" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- Bouton de soumission -->
            <div class="flex justify-end gap-2">
                <button type="button" id="cancel-appointment-btn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-300">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">
                    Enregistrer le rendez-vous
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Script pour l'autocomplete des patients et la validation du formulaire
document.addEventListener('DOMContentLoaded', function() {
    const patientSearch = document.getElementById('patient-search');
    const selectedPatientId = document.getElementById('selected-patient-id');
    const patientAutocomplete = document.getElementById('patient-autocomplete');
    const searchError = document.getElementById('search-error');
    const appointmentForm = document.getElementById('appointment-form');
    const cancelAppointmentBtn = document.getElementById('cancel-appointment-btn');
    
    // Gestion du bouton annuler
    if (cancelAppointmentBtn) {
        cancelAppointmentBtn.addEventListener('click', function() {
            const appointmentModal = document.getElementById('appointment-modal');
            if (appointmentModal) {
                appointmentModal.classList.add('hidden');
                // Réinitialiser le formulaire
                appointmentForm.reset();
                selectedPatientId.value = '';
                document.getElementById('appointment-date-hidden').value = '';
                patientAutocomplete.classList.add('hidden');
                searchError.classList.add('hidden');
                patientSearch.classList.remove('border-red-500');
            }
        });
    }
    
    if (patientSearch) {
        // Gérer la recherche de patients - CORRIGÉE
        patientSearch.addEventListener('input', function() {
            const term = this.value.trim();
            
            if (term.length < 2) {
                patientAutocomplete.classList.add('hidden');
                selectedPatientId.value = '';
                return;
            }
            
            // Réinitialiser le patient sélectionné si l'utilisateur modifie la recherche
            selectedPatientId.value = '';
            searchError.classList.add('hidden');
            patientSearch.classList.remove('border-red-500');
            
            // Effectuer la requête au serveur pour obtenir les patients correspondants
            fetch(`/patients/search?term=${encodeURIComponent(term)}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Response text:', text);
                            throw new Error(`HTTP ${response.status}: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Patients trouvés:', data);
                    patientAutocomplete.innerHTML = '';
                    
                    if (!data || data.length === 0) {
                        patientAutocomplete.innerHTML = '<div class="p-2 text-gray-500 text-sm">Aucun patient trouvé</div>';
                        patientAutocomplete.classList.remove('hidden');
                        return;
                    }
                    
                    data.forEach(patient => {
                        const patientItem = document.createElement('div');
                        patientItem.className = 'p-2 cursor-pointer hover:bg-gray-100 border-b border-gray-100';
                        patientItem.innerHTML = `
                            <div class="font-medium text-sm">${patient.name}</div>
                            ${patient.phone ? `<div class="text-xs text-gray-500">${patient.phone}</div>` : ''}
                        `;
                        patientItem.dataset.id = patient.id;
                        patientItem.dataset.name = patient.name;
                        
                        patientItem.addEventListener('click', function() {
                            patientSearch.value = this.dataset.name;
                            selectedPatientId.value = this.dataset.id;
                            patientAutocomplete.classList.add('hidden');
                            searchError.classList.add('hidden');
                            patientSearch.classList.remove('border-red-500');
                        });
                        
                        patientAutocomplete.appendChild(patientItem);
                    });
                    
                    patientAutocomplete.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche de patients:', error);
                    patientAutocomplete.innerHTML = `<div class="p-2 text-red-500 text-sm">Erreur: ${error.message}</div>`;
                    patientAutocomplete.classList.remove('hidden');
                });
        });
        
        // Cacher l'autocomplétion lorsqu'on clique ailleurs sur la page
        document.addEventListener('click', function(e) {
            if (!patientSearch.contains(e.target) && !patientAutocomplete.contains(e.target)) {
                patientAutocomplete.classList.add('hidden');
            }
        });
        
        // Lorsque l'utilisateur quitte le champ, vérifier si un patient valide a été sélectionné
        patientSearch.addEventListener('blur', function() {
            setTimeout(() => {
                if (!selectedPatientId.value && patientSearch.value.trim()) {
                    searchError.classList.remove('hidden');
                    patientSearch.classList.add('border-red-500');
                }
            }, 200);
        });
    }
    
    // Gérer la soumission du formulaire - AMÉLIORÉE
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let hasErrors = false;
            
            // Vérifier si un patient a été sélectionné
            if (!selectedPatientId.value) {
                searchError.classList.remove('hidden');
                patientSearch.classList.add('border-red-500');
                hasErrors = true;
            }
            
            // Vérifier si une date a été sélectionnée
            const hiddenDateField = document.getElementById('appointment-date-hidden');
            const dateError = document.getElementById('date-error');
            const dateField = document.getElementById('appointment-date');
            
            if (!hiddenDateField.value) {
                dateError.classList.remove('hidden');
                dateField.classList.add('border-red-500');
                hasErrors = true;
            }
            
            if (hasErrors) {
                return false;
            }
            
            // Récupérer les données du formulaire
            const formData = new FormData(appointmentForm);

            const appointmentData = {
                patient_id: selectedPatientId.value,
                type: formData.get('appointment_type'),
                appointment_date: hiddenDateField.value,
                notes: formData.get('patient_notes') || null
            };
            
            console.log('Données à envoyer:', appointmentData);
            
            // Désactiver le bouton de soumission pendant la requête
            const submitBtn = appointmentForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enregistrement...';
            
            // Envoyer les données au serveur via fetch
            fetch('/appointments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(appointmentData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        let errorData;
                        try {
                            errorData = JSON.parse(text);
                        } catch {
                            errorData = { message: 'Erreur serveur: ' + response.status };
                        }
                        throw errorData;
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Réponse du serveur:', data);
                
                if (data.success) {
                    // Fermer le modal et réinitialiser le formulaire
                    const appointmentModal = document.getElementById('appointment-modal');
                    appointmentModal.classList.add('hidden');
                    appointmentForm.reset();
                    selectedPatientId.value = '';
                    hiddenDateField.value = '';
                    patientAutocomplete.classList.add('hidden');
                    searchError.classList.add('hidden');
                    patientSearch.classList.remove('border-red-500');
                    dateField.classList.remove('border-red-500');
                    dateError.classList.add('hidden');
                    // Afficher un message de succès
                    alert(data.message || 'Rendez-vous ajouté avec succès!');
                    
                    // Recharger la page si besoin
                    if (data.reload || window.location.pathname.includes('appointments') || window.location.pathname === '/') {
                        window.location.reload();
                    }
                } else {
                    // Traiter l'erreur
                    alert('Erreur: ' + (data.message || 'Une erreur est survenue'));
                    console.error('Erreur:', data);
                    
                    // Si l'erreur concerne le patient, mettre en évidence le champ
                    if (data.errors && data.errors.patient_id) {
                        searchError.textContent = data.errors.patient_id[0];
                        searchError.classList.remove('hidden');
                        patientSearch.classList.add('border-red-500');
                    }
                    
                    if (data.errors && data.errors.appointment_date) {
                        dateError.textContent = data.errors.appointment_date[0];
                        dateError.classList.remove('hidden');
                        dateField.classList.add('border-red-500');
                    }
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                let errorMessage = 'Une erreur est survenue lors de l\'enregistrement du rendez-vous';
                
                if (error.message) {
                    errorMessage = error.message;
                } else if (error.errors) {
                    const firstError = Object.values(error.errors)[0];
                    if (Array.isArray(firstError)) {
                        errorMessage = firstError[0];
                    } else {
                        errorMessage = firstError;
                    }
                }
                
                alert(errorMessage);
            })
            .finally(() => {
                // Réactiver le bouton
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    }
});
</script>