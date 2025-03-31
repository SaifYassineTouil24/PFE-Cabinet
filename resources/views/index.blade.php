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
                    </h3>
                    <div class="space-y-2 max-h-[320px] overflow-y-auto">
                        @foreach($AppoConsul as $appo)
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
                    <h3 class="text-base font-semibold mb-3 pb-3 border-b border-gray-200 text-red-500 flex items-center">
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
                        <span class="w-3 h-3 bg-blue-200 rounded-full"></span>
                        <span class="text-xs">1-5 RV</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 bg-blue-400 rounded-full"></span>
                        <span class="text-xs">6-10 RV</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 bg-blue-600 rounded-full"></span>
                        <span class="text-xs">11-15 RV</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 bg-blue-800 rounded-full"></span>
                        <span class="text-xs">>15 RV</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</body>

<script >
    // resources/js/appointment-actions.js

    // resources/js/appointment-actions.js

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


                    if (appointmentId && newStatus) {
                        updateAppointmentStatus(appointmentId, newStatus, evt.item, evt.from);
                    }
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
                })
                .catch(error => {
                    console.error("Error updating appointment status:", error);
                    // Remettre la carte dans son conteneur d'origine en cas d'erreur
                    if (fromContainer && item.parentNode !== fromContainer) {
                        fromContainer.appendChild(item);
                    }

                    // Afficher une notification d'erreur
                    showNotification("Erreur lors de la mise à jour du statut", "error");
                });
        }// Simple notification function
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

    // Calendar functionality
    function initCalendar() {
        const calendarBody = document.getElementById('calendar-body');
        const currentMonthEl = document.getElementById('current-month');
        const prevButton = document.getElementById('prev-month');
        const nextButton = document.getElementById('next-month');

        let currentDate = new Date();

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

            // Add days
            for (let day = 1; day <= daysInMonth; day++) {
                const dayEl = document.createElement('div');
                dayEl.textContent = day;
                dayEl.className = 'p-1 rounded-full hover:bg-gray-100 cursor-pointer';

                // Highlight today
                if (year === new Date().getFullYear() && month === new Date().getMonth() && day === new Date().getDate()) {
                    dayEl.classList.add('bg-blue-500', 'text-white', 'hover:bg-blue-600');
                }

                // For demo purposes, add random appointment indicators
                // In real app, you'd check actual appointment data
                const appointmentCount = Math.floor(Math.random() * 20);
                if (appointmentCount > 0) {
                    dayEl.classList.add('relative');
                    const indicator = document.createElement('span');
                    indicator.className = 'absolute bottom-0 right-0 w-2 h-2 rounded-full';

                    if (appointmentCount <= 5) indicator.classList.add('bg-blue-200');
                    else if (appointmentCount <= 10) indicator.classList.add('bg-blue-400');
                    else if (appointmentCount <= 15) indicator.classList.add('bg-blue-600');
                    else indicator.classList.add('bg-blue-800');

                    dayEl.appendChild(indicator);
                }

                calendarBody.appendChild(dayEl);
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
</script>

</html>

