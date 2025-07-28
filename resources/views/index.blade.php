<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Propre</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- Ensure Tailwind is compiled -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script src="{{ asset('js/appointment-actions.js') }}"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="flex h-screen bg-gray-100 m-0 p-0">
<!-- Sidebar (Fixed) -->
<x-sidebar class="w-64 fixed left-0 top-0 h-full bg-gray-200 shadow-md" />
<div class="flex-1 flex flex-col pl-64 bg-blue-100">  <!-- Header (Fixed) -->

        <x-header />

    <!-- Content (Scrollable) -->
    <div class="flex-1 overflow-auto p-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 grid grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl shadow-[0px_8px_20px_rgba(0,0,0,0.25)]  p-4">
                    <h3 class="text-base font-semibold mb-3 pb-3 border-b border-gray-200 text-gray-700 flex items-center">
                        <i class="fas fa-calendar-check mr-2"></i> Programmé
                    </h3>
                    <div class="space-y-2 max-h-[320px] overflow-y-auto">
                        @foreach($AppoPrograme as $appo)
                            <x-patient-card
                                :name="$appo->patient->name"
                                :type="$appo->type"
                                :status="$appo->status"
                                :appointment="$appo"
                                data-appointment-id="{{ $appo->id }}"
                                class="patient-card"
                            />
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-[0px_8px_20px_rgba(0,0,0,0.25)] p-4">
                    <h3 class="text-base font-semibold mb-3 pb-3 border-b border-gray-200 text-yellow-600 flex items-center">
                        <i class="fas fa-clock mr-2"></i> Salle d'attente
                    </h3>
                    <div class="space-y-2 max-h-[320px] overflow-y-auto">
                        @foreach($AppoAttend as $appo)
                            <x-patient-card
                                :name="$appo->patient->name"
                                :type="$appo->type"
                                :status="$appo->status"
                                :appointment="$appo"
                                data-appointment-id="{{ $appo->id }}"
                                class="patient-card"
                            />
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-[0px_8px_20px_rgba(0,0,0,0.25)] p-4">
                    <h3 class="text-base font-semibold mb-3 pb-3 border-b border-gray-200 text-orange-600 flex items-center">
                        <i class="fas fa-clipboard-list mr-2"></i> En preparation
                    </h3>
                    <div class="space-y-2 max-h-[320px] overflow-y-auto">
                        @foreach($AppoPrepa as $appo)
                            <x-patient-card
                                :name="$appo->patient->name"
                                :type="$appo->type"
                                :status="$appo->status"
                                :appointment="$appo"
                                data-appointment-id="{{ $appo->id }}"
                                class="patient-card"
                            />
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-[0px_8px_20px_rgba(0,0,0,0.25)] p-4">
                    <h3 class="text-base font-semibold mb-3 pb-3 border-b border-gray-200 text-blue-700 flex items-center">
                        <i class="fas fa-stethoscope mr-2"></i> En consultation
                        <span class="ml-2 text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full">Max: 1</span>
                    </h3>
                    <div id="consultation-container" class="space-y-2 max-h-[320px] overflow-y-auto">
                        @forelse($AppoConsul as $appo)
                            <x-patient-card
                                :name="$appo->patient->name"
                                :type="$appo->type"
                                :status="$appo->status"
                                :appointment="$appo"
                                data-appointment-id="{{ $appo->id }}"
                                class="patient-card"
                            />
                        @empty
                            <!-- Message d'information si aucun patient en consultation -->
                            <div id="consultation-empty-message" class="text-center text-gray-500 text-sm py-4">
                                <i class="fas fa-info-circle mb-2"></i>
                                <p>Aucun patient en consultation</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-[0px_8px_20px_rgba(0,0,0,0.25)] p-4">
                    <h3 class="text-base font-semibold mb-3 pb-3 border-b border-gray-200 text-green-600 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i> Completé Aujourd'hui
                    </h3>
                    <div class="space-y-2 max-h-[320px] overflow-y-auto">
                        @foreach($AppoCompleted as $appo)
                            <x-patient-card
                                :name="$appo->patient->name"
                                :type="$appo->type"
                                :status="$appo->status"
                                :appointment="$appo"
                                data-appointment-id="{{ $appo->id }}"
                                class="patient-card"
                            />
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-[0px_8px_20px_rgba(0,0,0,0.25)] p-4">
                    <h3 class="text-base font-semibold mb-3 pb-3 border-b border-gray-200 text-red-700 flex items-center">
                        <i class="fas fa-ban mr-2"></i> Annulé
                    </h3>
                    <div class="space-y-2 max-h-[320px] overflow-y-auto">
                        @foreach($AppoAnnule as $appo)
                            <x-patient-card
                                :name="$appo->patient->name"
                                :type="$appo->type"
                                :status="$appo->status"
                                :appointment="$appo"
                                data-appointment-id="{{ $appo->id }}"
                                class="patient-card"
                            />
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-4 h-fit">
                <div class="mb-3">
                    <h3 class="text-base font-semibold text-gray-700 flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i> Calendrier
                    </h3>
                    <div class="flex items-center justify-between my-3">
                        <button id="prev-month" class="text-gray-600 hover:text-blue-500">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <h4 id="current-month" class="text-base font-medium">Juin 2023</h4>
                        <button id="next-month" class="text-gray-600 hover:text-blue-500">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div id="calendar-body" class="grid grid-cols-7 gap-1 text-center text-sm">
                    <!-- Calendar days -->
                </div>
                <div class="mt-3 space-y-1">
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                        <span class="text-xs">1-5 RV</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                        <span class="text-xs">6-10 RV</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 bg-orange-600 rounded-full"></span>
                        <span class="text-xs">11-15 RV</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 bg-red-700 rounded-full"></span>
                        <span class="text-xs">>15 RV</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fonction pour mettre à jour l'affichage du message "consultation vide"
    function updateConsultationEmptyMessage() {
        const consultationContainer = document.getElementById('consultation-container');
        const patientCards = consultationContainer.querySelectorAll('.patient-card');
        const emptyMessage = document.getElementById('consultation-empty-message');
        
        if (patientCards.length === 0) {
            // Aucun patient, afficher le message
            if (!emptyMessage) {
                const messageDiv = document.createElement('div');
                messageDiv.id = 'consultation-empty-message';
                messageDiv.className = 'text-center text-gray-500 text-sm py-4';
                messageDiv.innerHTML = `
                    <i class="fas fa-info-circle mb-2"></i>
                    <p>Aucun patient en consultation</p>
                `;
                consultationContainer.appendChild(messageDiv);
            }
        } else {
            // Il y a des patients, masquer le message
            if (emptyMessage) {
                emptyMessage.remove();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Sortable.js for each appointment container
        const containers = document.querySelectorAll('.space-y-2');

        containers.forEach(container => {
            new Sortable(container, {
                group: 'appointments',
                animation: 150,
                onEnd: function(evt) {
                    const appointmentId = evt.item.getAttribute('data-appointment-id');
                    const newStatus = determineStatus(evt.to);
                    console.log(appointmentId, newStatus);

                    // Vérification spéciale pour la section consultation
                    if (newStatus === 'consulting') {
                        const consultationContainer = document.getElementById('consultation-container');
                        const patientCards = consultationContainer.querySelectorAll('.patient-card');
                        
                        // Si il y a plus d'un patient après le déplacement, bloquer
                        if (patientCards.length > 1) {
                            // Remettre la carte dans son conteneur d'origine
                            evt.from.appendChild(evt.item);
                            showNotification("Il y a déjà un patient en consultation. Veuillez terminer la consultation en cours avant d'en commencer une nouvelle.", "error");
                            return;
                        }
                    }

                    if (appointmentId && newStatus) {
                        updateAppointmentStatus(appointmentId, newStatus, evt.item, evt.from);
                    }
                    
                    // Mettre à jour immédiatement l'affichage
                    updateConsultationEmptyMessage();
                }
            });
        });

        // Determine the status based on the container
        function determineStatus(container) {
            const header = container.closest('div.bg-white').querySelector('h3');
            if (!header) return null;

            if (header.textContent.includes('Programmé')) return 'scheduled';
            if (header.textContent.includes('Salle d\'attente')) return 'waiting';
            if (header.textContent.includes('En preparation')) return 'preparing';
            if (header.textContent.includes('En consultation')) return 'consulting';
            if (header.textContent.includes('Completé')) return 'completed';
            if (header.textContent.includes('Annulé')) return 'canceled';

            return null;
        }

        // Update the appointment status via AJAX
        function updateAppointmentStatus(appointmentId, newStatus, item, fromContainer) {
            fetch('/appointments/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    appointment_id: appointmentId,
                    status: newStatus
                })
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            console.error("Failed to update appointment status", data.message || data.error);
                            fromContainer.appendChild(item);
                            throw new Error("Server responded with status: " + response.status);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Status updated successfully");

                    // Mise à jour des couleurs de la carte
                    if (data.success && data.colors) {
                        // Supprime les anciennes classes de couleur
                        const oldBgClass = item.querySelector('.inline-block');
                        const oldBorderClass = item.classList.value.split(' ').find(cls => cls.startsWith('border-'));
                        const oldStripeClass = item.querySelector('.w-1.h-8');

                        if (oldBgClass) {
                            // Mettre à jour les classes du badge de type
                            oldBgClass.className = oldBgClass.className
                                .replace(/bg-\w+-100/, data.colors.bg)
                                .replace(/text-\w+-700/, data.colors.text);
                        }

                        if (oldBorderClass) {
                            // Mettre à jour la bordure de la carte
                            item.classList.remove(oldBorderClass);
                            item.classList.add(data.colors.border);
                        }

                        if (oldStripeClass) {
                            // Mettre à jour la bande colorée verticale
                            oldStripeClass.className = oldStripeClass.className
                                .replace(/bg-\w+-100/, data.colors.bg);
                        }

                        // Mettre à jour l'attribut data-status (optionnel)
                        item.setAttribute('data-status', newStatus);

                        // Afficher une notification
                        showNotification("Statut du rendez-vous mis à jour avec succès", "success");
                    }
                    
                    // Mettre à jour le message de consultation vide
                    updateConsultationEmptyMessage();
                })
                .catch(error => {
                    console.error("Error updating appointment status:", error);
                    // Remettre la carte dans son conteneur d'origine en cas d'erreur
                    if (fromContainer && item.parentNode !== fromContainer) {
                        fromContainer.appendChild(item);
                    }

                    // Afficher une notification d'erreur
                    showNotification("Erreur lors de la mise à jour du statut", "error");
                    
                    // Mettre à jour le message de consultation vide même en cas d'erreur
                    updateConsultationEmptyMessage();
                });
        }

        // Simple notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-md ${
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            } text-white max-w-md z-50`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Initialize calendar
        initCalendar();
    });

    // Dans index.blade.php - Remplacer la fonction initCalendar() existante

function initCalendar() {
    const calendarBody = document.getElementById('calendar-body');
    const currentMonthEl = document.getElementById('current-month');
    const prevButton = document.getElementById('prev-month');
    const nextButton = document.getElementById('next-month');

    let currentDate = new Date();

   // Remplacez la fonction renderCalendar dans votre index.blade.php par cette version corrigée :

function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();

    // Update month display
    const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    currentMonthEl.textContent = `${monthNames[month]} ${year}`;

    // Clear previous calendar
    calendarBody.innerHTML = '';

    // Add day names
    const dayNames = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];
    dayNames.forEach(day => {
        const dayEl = document.createElement('div');
        dayEl.textContent = day;
        dayEl.className = 'font-medium text-gray-500';
        calendarBody.appendChild(dayEl);
    });

    // Get first day of month and number of days
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // Add empty cells for days before first of month
    for (let i = 0; i < firstDay; i++) {
        const emptyEl = document.createElement('div');
        calendarBody.appendChild(emptyEl);
    }

    // Add days with click functionality
    for (let day = 1; day <= daysInMonth; day++) {
        const dayEl = document.createElement('div');
        dayEl.textContent = day;
        dayEl.className = 'p-1 rounded-full hover:bg-gray-100 cursor-pointer transition-colors duration-200';
        
        // CORRECTION : Créer la date string directement sans passer par Date object
        const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        // Add click event to navigate to that date
        dayEl.addEventListener('click', function() {
            // Show loading state
            dayEl.classList.add('bg-gray-200');
            
            // Navigate to the appointments for this date
            window.location.href = `/appointments/${dateString}`;
        });

        // Highlight today
        const today = new Date();
        if (year === today.getFullYear() && 
            month === today.getMonth() && 
            day === today.getDate()) {
            dayEl.classList.remove('hover:bg-gray-100');
            dayEl.classList.add('bg-blue-500', 'text-white', 'hover:bg-blue-600');
        }

        // Load appointment counts for this month
        loadAppointmentCountForDay(year, month, day, dayEl);

        calendarBody.appendChild(dayEl);
    }
}

    // Function to load appointment count for a specific day
    function loadAppointmentCountForDay(year, month, day, dayEl) {
        // Format the date as YYYY-MM-DD
        const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        // You can implement an AJAX call here to get real appointment counts
        // For now, using random data as in the original code
        const appointmentCount = Math.floor(Math.random() * 20);
        
        if (appointmentCount > 0) {
            dayEl.classList.add('relative');
            const indicator = document.createElement('span');
            indicator.className = 'absolute bottom-0 right-0 w-2 h-2 rounded-full';
            indicator.title = `${appointmentCount} rendez-vous`;

            if (appointmentCount <= 5) indicator.classList.add('bg-green-500');
            else if (appointmentCount <= 10) indicator.classList.add('bg-yellow-500');
            else if (appointmentCount <= 15) indicator.classList.add('bg-orange-600');
            else indicator.classList.add('bg-red-700');

            dayEl.appendChild(indicator);
        }
    }

    // Initial render
    renderCalendar(currentDate);

    // Event listeners for previous/next buttons
    prevButton.addEventListener('click', () => {
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
        renderCalendar(currentDate);
    });

    nextButton.addEventListener('click', () => {
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1);
        renderCalendar(currentDate);
    });
}

// Optional: Function to load real appointment counts via AJAX
function loadMonthlyAppointmentCounts(year, month) {
    const yearMonth = `${year}-${String(month + 1).padStart(2, '0')}`;
    
    fetch(`/appointments/monthly-counts/${yearMonth}`)
        .then(response => response.json())
        .then(data => {
            // Update calendar with real appointment counts
            Object.keys(data).forEach(date => {
                const day = parseInt(date.split('-')[2]);
                const dayEl = document.querySelector(`#calendar-body div:nth-child(${day + 7})`); // +7 for day headers
                if (dayEl && data[date] > 0) {
                    const count = data[date];
                    dayEl.classList.add('relative');
                    
                    const indicator = document.createElement('span');
                    indicator.className = 'absolute bottom-0 right-0 w-2 h-2 rounded-full';
                    indicator.title = `${count} rendez-vous`;

                    if (count <= 5) indicator.classList.add('bg-green-500');
                    else if (count <= 10) indicator.classList.add('bg-yellow-500');
                    else if (count <= 15) indicator.classList.add('bg-orange-600');
                    else indicator.classList.add('bg-red-700');

                    dayEl.appendChild(indicator);
                }
            });
        })
        .catch(error => {
            console.log('Erreur lors du chargement des comptes mensuels:', error);
        });
}
</script>

</body>
</html>