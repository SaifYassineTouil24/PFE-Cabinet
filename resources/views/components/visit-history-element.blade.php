<tr class="hover:bg-gray-50 clickable-row cursor-pointer transition-colors duration-200" data-appointment-id="{{ $appointmentId }}">
    <td class="w-[100px] h-[20px] px-4 py-2 border-b border-gray-300 text-center">{{ $date }}</td>
    <td class="w-[100px] h-[20px] px-4 py-2 border-b border-gray-300 text-center">{{ $type }}</td>
    <td class="w-[100px] h-[20px] px-4 py-2 border-b border-gray-300 text-center">{{ $payement }} DH</td>
    <td class="w-[100px] h-[20px] px-4 py-2 border-b border-gray-300 text-center">
        <form action="{{ route('appointments.toggle-mutuelle') }}" method="POST" onclick="event.stopPropagation();">
            @csrf
            <input type="hidden" name="appointment_id" value="{{ $appointmentId }}">
            <input type="checkbox" name="mutuelle" class="w-5 h-5 text-green-500 rounded toggle-mutuelle"
                   data-appointment-id="{{ $appointmentId }}"
                   onchange="this.form.submit()" {{ $mutuelle ? 'checked' : '' }}>
        </form>
    </td>
</tr>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Gestion des clics sur les lignes du tableau
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function(e) {
                // Vérifier si le clic provient de la checkbox ou du formulaire
                if (e.target.type === 'checkbox' || e.target.tagName === 'FORM') {
                    return; // Ne pas rediriger si c'est un clic sur la checkbox
                }
                
                // Retirer la sélection de toutes les autres lignes
                document.querySelectorAll('.clickable-row').forEach(otherRow => {
                    otherRow.classList.remove('selected');
                });
                
                // Ajouter la sélection à la ligne cliquée
                this.classList.add('selected');
                
                const appointmentId = this.dataset.appointmentId;
                if (appointmentId) {
                    // Petite pause pour voir l'effet de sélection avant la redirection
                    setTimeout(() => {
                        window.location.href = `/appointments/${appointmentId}/details`;
                    }, 200);
                }
            });
        });

        // Gestion des checkboxes avec AJAX
        const checkboxes = document.querySelectorAll('.toggle-mutuelle');

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', function (e) {
                // Empêcher la propagation pour éviter le clic sur la ligne
                e.stopPropagation();
                
                const appointmentId = this.dataset.appointmentId;
                const mutuelle = this.checked ? 1 : 0;

                // Envoie une requête AJAX pour mettre à jour l'état
                fetch('/appointments/toggle-mutuelle-ajax', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        appointment_id: appointmentId,
                        mutuelle: mutuelle
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Mise à jour réussie : ', data.message);
                    } else {
                        console.error('Erreur de mise à jour : ', data.message);
                        // Rétablir l'état précédent en cas d'erreur
                        this.checked = !this.checked;
                    }
                })
                .catch((error) => {
                    console.error('Erreur lors de l\'envoi de la requête : ', error);
                    // Rétablir l'état précédent en cas d'erreur
                    this.checked = !this.checked;
                });
            });
        });
    });
</script>