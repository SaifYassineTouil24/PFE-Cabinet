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

                if (appointmentId && newStatus) {
                    updateAppointmentStatus(appointmentId, newStatus);
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
    function updateAppointmentStatus(appointmentId, status) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/appointments/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                appointment_id: appointmentId,
                status: status
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Appointment status updated successfully');
                } else {
                    console.error('Failed to update appointment status');
                    // You might want to move the card back to its original position
                }
            })
            .catch(error => {
                console.error('Error updating appointment status:', error);
            });
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
