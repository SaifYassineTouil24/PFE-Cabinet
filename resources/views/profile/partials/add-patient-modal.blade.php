<!-- Modal pour Ajouter Patient (Design amélioré) -->
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
