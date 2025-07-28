<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Propre</title>
    @vite('resources/css/app.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/appointment-actions.js') }}"></script>
    <!-- jQuery and Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>
<body class="bg-gray-100 text-gray-700 flex m-0 p-0">

<!-- Sidebar (Fixed) -->
<x-sidebar class="w-64 fixed left-0 top-0 h-full bg-gray-100 shadow-md"/>

<!-- Main Content -->
<div class="flex-1 flex flex-col pl-64 bg-gray-50 overflow-y-auto">
    <x-header/>

    <div class="p-6">

            <div class="flex items-center justify-between p-4">
                <h1 class="text-2xl font-bold text-gray-800">Détails du Rendez-vous</h1>
                <div class="flex space-x-4">
                    <span class="text-gray-500 flex items-center">
                    <i class="fas fa-calendar mr-2"></i>
                    <span id="appointment-date" class="font-medium">21 Juin 2025</span>
                </span>
                </div>
            </div>



        <form action="{{ route('appointments.edit', $appointment->ID_RV) }}" method="POST">
            @csrf
        <div class="grid grid-cols-1 gap-4 p-4">
            <div class="bg-white rounded-2xl shadow-md p-6 space-y-2 overflow-auto">
                <h3 class="text-lg font-semibold mb-3 p-2.5 border-b border-gray-300 text-blue-500 text-center">
                    <i class="fas fa-info-circle mr-2"></i> Description du Cas
                </h3>
                <div class="space-y-6">


                        <!-- Cas Description -->
                        <textarea
                            id="case-description"
                            name="case_description"
                            rows="4"
                            placeholder="Décrivez les plaintes et symptômes du patient"
                            class="w-full p-3 border border-gray-300 rounded-lg text-sm resize-y min-h-[100px] focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"
                        >{{ $appointment->caseDescription->case_description ?? '' }}</textarea>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1">
                                <label for="blood-pressure"
                                       class="block text-sm font-medium text-gray-700">Tension</label>
                                <input
                                    type="text"
                                    id="blood-pressure"
                                    name="blood_pressure"
                                    placeholder="120/80"
                                    class="w-full p-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ $appointment->caseDescription->blood_pressure ?? '' }}"
                                />
                            </div>

                            <div class="col-span-1">
                                <label for="pulse" class="block text-sm font-medium text-gray-700">Pouls</label>
                                <input
                                    type="text"
                                    id="pulse"
                                    name="pulse"
                                    placeholder="72"
                                    class="w-full p-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ $appointment->caseDescription->pulse ?? '' }}"
                                />
                            </div>

                            <div class="col-span-1">
                                <label for="temperature"
                                       class="block text-sm font-medium text-gray-700">Température</label>
                                <input
                                    type="text"
                                    id="temperature"
                                    name="temperature"
                                    placeholder="37.0"
                                    class="w-full p-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ $appointment->caseDescription->temperature ?? '' }}"
                                />
                            </div>

                            <div class="col-span-1">
                                <label for="tall" class="block text-sm font-medium text-gray-700">Taille (cm)</label>
                                <input
                                    type="text"
                                    id="tall"
                                    name="tall"
                                    placeholder="170"
                                    class="w-full p-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ $appointment->caseDescription->tall ?? '' }}"
                                />
                            </div>
                        </div>

                        <!-- Bouton de soumission -->

                </div>


            </div>

        </div>

            <!-- Plan de Traitement -->
        <div class="grid grid-cols-1 gap-4 p-4">
            <div id="treatment-section" class="bg-white rounded-2xl shadow-md p-6 space-y-2 overflow-auto">
    <div class="flex justify-between items-center mb-3 border-b border-gray-300 pb-2">
        <h3 class="text-lg font-semibold text-blue-500 flex items-center">
            <i class="fas fa-pills mr-2"></i> Plan de Traitement
        </h3>
        <button onclick="printSection('treatment-section')" class="text-sm text-green-600 hover:text-green-800 flex items-center gap-1">
            <i class="fas fa-print"></i> Imprimer
        </button>
    </div>

                <div class="space-y-6">
                    <!-- Médicaments -->
                    <div class="space-y-3">
                        <label for="medication-list" class="block text-sm font-medium text-gray-700">Médicaments</label>
                        <div id="medication-list" class="space-y-4">
                            @foreach($appointment->medicaments as $index => $med)
                                <div
                                    class="flex flex-wrap items-center gap-4 p-4 bg-gray-100 rounded-xl medication-item">
                                    <select name="medicaments[{{ $index }}][id]" class="medicament-select w-full"
                                            data-selected="{{ $med->ID_Medicament }}"></select>
                                    <input type="text" name="medicaments[{{ $index }}][dosage]"
                                           value="{{ $med->pivot->dosage }}" placeholder="Dosage (ex: 500mg)"/>
                                    <input type="text" name="medicaments[{{ $index }}][frequence]"
                                           value="{{ $med->pivot->frequence }}"
                                           placeholder="Fréquence (ex: 2 fois/jour)"/>
                                    <input type="text" name="medicaments[{{ $index }}][duree]"
                                           value="{{ $med->pivot->duree }}" placeholder="Durée (ex: 5 jours)"/>
                                    <button class="remove-medication text-red-500"><i class="fas fa-trash"></i></button>
                                </div>
                            @endforeach
                        </div>
                        <button
                            id="add-medication"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition"
                            type="button"
                        >
                            <i class="fas fa-plus"></i> Ajouter un Médicament
                        </button>
                    </div>

                    <!-- Instructions -->
                    <div class="space-y-2">
                        <label for="treatment-instructions"
                               class="block text-sm font-medium text-gray-700">Instructions</label>
                        <textarea
                            id="treatment-instructions"
                            rows="3"
                            placeholder="Instructions spéciales pour le traitement"
                            class="w-full p-3 border border-gray-300 rounded-lg text-sm resize-y min-h-[100px] focus:outline-none focus:ring-2 focus:ring-blue-500"
                        ></textarea>



                    </div>


                </div>
            </div>
        </div>
            <div class="grid grid-cols-1 gap-4 p-4">
                <div class="bg-white rounded-2xl shadow-md p-6 space-y-2 overflow-auto">
                    <div class="flex justify-between items-center mb-3 border-b border-gray-300 pb-2">
    <h3 class="text-lg font-semibold text-blue-500 flex items-center">
        <i class="fas fa-flask mr-2"></i> Demande d'Analyses
    </h3>
    <button onclick="printSection('analyse-section')" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
        <i class="fas fa-print"></i> Imprimer
    </button>
</div>



                    <!-- Analysis List -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">Analyses</label>
                        <div id="analyse-list" class="space-y-4">
                            @foreach($appointment->analyses as $index => $ana)
                                <div

                                    class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200 analyse-item">
                                    <select name="analyses[{{ $index }}][id]" class="analyse-select w-full"
                                            data-selected="{{ $ana->ID_Analyse }}"></select>
                                    <button class="remove-analyse text-red-500"><i class="fas fa-trash"></i></button>
                                </div>
                            @endforeach
                        </div>
                        <button
                            id="add-analyse"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition"
                            type="button"
                        >
                            <i class="fas fa-plus"></i> Ajouter un Analyse
                        </button>
                    </div>
                </div>

                </div>
                
            <div class="flex justify-center mt-6">
    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 focus:ring-2 focus:ring-green-300">
        Sauvegarder
    </button>
</div>

        </form>


        </div>
    </div>



</body>
<script>

    function initializeMedicationSelect() {
        $('.medicament-select').select2({
            placeholder: "Rechercher un médicament",
            minimumInputLength: 1,
            ajax: {
                url: '/medicaments/search',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).each(function () {
            const selectedId = $(this).data('selected');
            if (selectedId) {
                const $select = $(this);
                $.ajax({
                    type: 'GET',
                    url: '/medicaments/search?q=',
                    success: function (data) {
                        const match = data.find(item => item.id == selectedId);
                        if (match) {
                            const option = new Option(match.text, match.id, true, true);
                            $select.append(option).trigger('change');
                        }
                    }
                });
            }
        });
    }
    

    function initializeAnalyseSelect() {
        $('.analyse-select').select2({
            placeholder: "Rechercher un analyse",
            minimumInputLength: 1,
            ajax: {
                url: '/analyses/search',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).each(function () {
            const selectedId = $(this).data('selected');
            if (selectedId) {
                const $select = $(this);
                $.ajax({
                    type: 'GET',
                    url: '/analyses/search?q=',
                    success: function (data) {
                        const match = data.find(item => item.id == selectedId);
                        if (match) {
                            const option = new Option(match.text, match.id, true, true);
                            $select.append(option).trigger('change');
                        }
                    }
                });
            }
        });
    }

    // Apply on page load and after adding new medication rows
    $(document).ready(function () {
        initializeMedicationSelect();

        $('#add-medication').click(function () {
            const medicationList = $('#medication-list');
            const index = $('.medication-item').length; // 🔥 this gives us the correct key

            const newMedication = $(`
        <div class="flex flex-wrap items-center gap-4 p-4 bg-gray-100 rounded-xl medication-item">
            <select name="medicaments[${index}][id]" class="medicament-select w-full"></select>
            <input type="text" name="medicaments[${index}][dosage]" placeholder="Dosage (ex: 500mg)"/>
            <input type="text" name="medicaments[${index}][frequence]" placeholder="Fréquence (ex: 2 fois/jour)"/>
            <input type="text" name="medicaments[${index}][duree]" placeholder="Durée (ex: 5 jours)"/>
            <button class="remove-medication text-red-500"><i class="fas fa-trash"></i></button>
        </div>
    `);

            medicationList.append(newMedication);
            initializeMedicationSelect();

            newMedication.find('.remove-medication').click(function () {
                newMedication.remove();
            });
        });

    });
    $(document).on('click', '.remove-medication', function () {
        $(this).closest('.medication-item').remove();
    });

    $(document).ready(function () {
        initializeAnalyseSelect();

        $('#add-analyse').click(function () {
            const analyseList = $('#analyse-list');
            const index = $('.analyse-item').length; // 🔥 this gives us the correct key

            const newAnalyse = $(`
        <div
            class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200 analyse-item">
            <select name="analyses[${index}][id]" class="analyse-select w-full"></select>
            <button class="remove-analyse text-red-500"><i class="fas fa-trash"></i></button>
        </div>
    `);

            analyseList.append(newAnalyse);
            initializeAnalyseSelect();

            newAnalyse.find('.remove-analyse').click(function () {
                newAnalyse.remove();
            });
        });

    });
    $(document).on('click', '.remove-analyse', function () {
        $(this).closest('.analyse-item').remove();
    });
    




</script>
</html>
