
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Clinic Propre - Rapports</title>
    @vite('resources/css/app.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        .mode-selector {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 4px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .mode-option {
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            font-weight: 500;
            text-align: center;
            min-width: 80px;
        }
        .mode-option:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .mode-option.active {
            background: white;
            color: #667eea;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
        }
        .month-selector {
            background: linear-gradient(135deg, #f093fb 0%,rgb(229, 102, 119) 100%);
            border-radius: 8px;
            padding: 8px 16px;
            color: white;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 120px;
        }
        .month-selector:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 87, 108, 0.25);
        }
        .month-selector option {
            color: #333;
            background: white;
        }
        .controls-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            min-width: 0;
            overflow-x: auto;
        }
        .year-controls {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 10px;
            padding: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .year-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }
        .year-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }
        .year-display {
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            padding: 0 16px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        .period-indicator {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            margin-bottom: 24px;
        }
        .chart-card {
            background: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .chart-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .fade-transition {
            transition: opacity 0.3s ease;
        }
        .fade-out {
            opacity: 0.3;
        }
        
        /* Amélioration de la responsivité */
        @media (max-width: 768px) {
            .controls-container {
                padding: 15px;
                gap: 12px !important;
            }
            
            .mode-selector {
                min-width: 160px;
            }
            
            .mode-option {
                padding: 8px 16px;
                font-size: 14px;
            }
            
            .year-controls {
                min-width: 140px;
            }
            
            .month-selector {
                min-width: 100px;
                padding: 6px 12px;
                font-size: 14px;
            }
        }

        /* S'assurer que le bouton PDF reste visible */
        #generate-pdf {
            min-width: 120px;
            position: relative !important;
            z-index: 10 !important;
            display: inline-flex !important;
            align-items: center !important;
        }

        @media (min-width: 640px) {
            #generate-pdf {
                min-width: 160px;
            }
        }

        /* Amélioration du header pour éviter le débordement */
        .header-controls {
            flex-wrap: wrap;
            gap: 20px;
        }

        @media (max-width: 1024px) {
            .header-controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .controls-container {
                justify-content: center;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <x-sidebar class="w-64 fixed left-0 top-0 h-full bg-gray-200 shadow-lg" />
    
    <div class="flex-1 flex flex-col pl-64">
        <x-header />
        
        <div class="p-4 md:p-6">
            <div class="flex justify-between items-center mb-6 header-controls">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-chart-line text-blue-500"></i> 
                    Rapports Statistiques
                </h1>
                
                <div class="controls-container flex flex-wrap items-center gap-4 md:gap-6">
                    <!-- Sélecteur de mode -->
                    <div class="mode-selector flex">
                        <div class="mode-option active" data-mode="year">
                            <i class="fas fa-calendar-alt mr-2"></i> Année
                        </div>
                        <div class="mode-option" data-mode="month">
                            <i class="fas fa-calendar-day mr-2"></i> Mois
                        </div>
                    </div>
                    
                    <!-- Contrôles d'année -->
                    <div class="year-controls">
                        <button id="prev-year" class="year-btn">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="current-year-display" class="year-display">{{ now()->year }}</span>
                        <button id="next-year" class="year-btn">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    <!-- Sélecteur de mois (masqué par défaut) -->
                    <div id="month-controls" class="hidden">
                        <select id="month-selector" class="month-selector">
                            <option value="1">Janvier</option>
                            <option value="2">Février</option>
                            <option value="3">Mars</option>
                            <option value="4">Avril</option>
                            <option value="5">Mai</option>
                            <option value="6">Juin</option>
                            <option value="7">Juillet</option>
                            <option value="8">Août</option>
                            <option value="9">Septembre</option>
                            <option value="10">Octobre</option>
                            <option value="11">Novembre</option>
                            <option value="12">Décembre</option>
                        </select>
                    </div>
                    
                    <!-- Bouton PDF - Ajusté pour être toujours visible -->
                    <div class="flex-shrink-0">
                        <button id="generate-pdf" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-2 px-4 md:py-3 md:px-6 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl hover:transform hover:-translate-y-1 whitespace-nowrap">
                            <i class="fas fa-file-pdf mr-2"></i> 
                            <span class="hidden sm:inline">Générer PDF</span>
                            <span class="sm:hidden">PDF</span>
                        </button>
                    </div>
                </div>
            </div>

            <div id="loading-indicator" class="text-center py-8 hidden">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                <p class="mt-4 text-gray-600 font-medium">Chargement des données...</p>
            </div>
            
            <div id="error-display" class="bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-4 rounded-r-lg mb-6 hidden" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-3"></i>
                    <div>
                        <strong class="font-bold">Erreur!</strong>
                        <span class="block sm:inline ml-2" id="error-message"></span>
                    </div>
                </div>
            </div>

            <!-- Indicateur de période active -->
            <div id="period-indicator" class="period-indicator">
                <i class="fas fa-info-circle mr-2"></i>
                <span id="period-text">Données pour l'année {{ now()->year }}</span>
            </div>

            <!-- Graphiques -->
            <div id="charts-container" class="fade-transition">
                <!-- Première ligne de graphiques - Revenus et Rendez-vous -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 hidden">
                    <div class="chart-card">
                        <h3 class="chart-title">
                            <i class="fas fa-money-bill-wave text-green-600"></i>
                            <span id="income-chart-title">Revenus par Mois (DH)</span>
                        </h3>
                        <div class="h-64">
                            <canvas id="incomeChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-card">
                        <h3 class="chart-title">
                            <i class="fas fa-calendar-check text-blue-500"></i>
                            <span id="appointments-chart-title">Rendez-vous par Mois</span>
                        </h3>
                        <div class="h-64">
                            <canvas id="appointmentsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Deuxième ligne de graphiques -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="chart-card">
                        <h3 class="chart-title">
                            <i class="fas fa-vial text-green-500"></i>
                            Répartition des analyses
                        </h3>
                        <div class="h-64">
                            <canvas id="analysisDistributionChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-card">
                        <h3 class="chart-title">
                            <i class="fas fa-pills text-purple-500"></i>
                            Médicaments les plus prescrits
                        </h3>
                        <div class="h-64">
                            <canvas id="medicationsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Troisième ligne de graphiques -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="chart-card">
                        <h3 class="chart-title">
                            <i class="fas fa-user-friends text-blue-500"></i>
                            Répartition par âge
                        </h3>
                        <div class="h-64">
                            <canvas id="ageDistributionChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-card">
                        <h3 class="chart-title">
                            <i class="fas fa-venus-mars text-pink-500"></i>
                            Répartition par genre
                        </h3>
                        <div class="h-64">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Quatrième ligne de graphiques -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="chart-card">
                        <h3 class="chart-title">
                            <i class="fas fa-calendar-alt text-green-500"></i>
                            Types de rendez-vous
                        </h3>
                        <div class="h-64">
                            <canvas id="appointmentTypesChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-card">
                        <h3 class="chart-title">
                            <i class="fas fa-clipboard-check text-orange-500"></i>
                            Statut des rendez-vous
                        </h3>
                        <div class="h-64">
                            <canvas id="appointmentStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Cinquième ligne de graphiques -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="chart-card">
                        <h3 class="chart-title">
                            <i class="fas fa-id-card text-teal-500"></i>
                            Distribution des mutuelles
                        </h3>
                        <div class="h-64">
                            <canvas id="mutuellesChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-card">
                        <h3 class="chart-title">
                            <i class="fas fa-heartbeat text-red-500"></i>
                            Conditions chroniques
                        </h3>
                        <div class="h-64">
                            <canvas id="chronicConditionsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuration initiale
            let currentYear = new Date().getFullYear();
            let currentMonth = new Date().getMonth() + 1;
            let currentMode = 'year';
            let charts = {};
            
            // Initialiser le mois actuel dans le sélecteur
            document.getElementById('month-selector').value = currentMonth;
            
            // Fonction pour obtenir le nom du mois
            function getMonthName(monthNumber) {
                const months = [
                    '', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                    'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ];
                return months[monthNumber] || 'Mois inconnu';
            }
            
            // Initialisation des graphiques
            function initCharts() {
                const chartConfigs = {
                     incomeChart: {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Revenus',
                data: [],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4, // pour lisser les courbes
                pointBackgroundColor: 'white',
                pointBorderColor: 'rgba(75, 192, 192, 1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    },

    // Bar chart pour les rendez-vous
    appointmentsChart: {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Rendez-vous',
                data: [],
                backgroundColor: 'rgba(255, 159, 64, 0.6)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    },
                    analysisDistributionChart: {
                        type: 'pie',
                        data: {
                            labels: [],
                            datasets: [{
                                data: [],
                                backgroundColor: [
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(153, 102, 255, 0.8)',
                                    'rgba(255, 159, 64, 0.8)',
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 206, 86, 0.8)'
                                ],
                                borderWidth: 2,
                                borderColor: 'white'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                }
                            }
                        }
                    },
                    medicationsChart: {
                        type: 'bar',
                        data: {
                            labels: [],
                            datasets: [{
                                label: 'Prescriptions',
                                data: [],
                                backgroundColor: 'rgba(153, 102, 255, 0.8)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 2,
                                borderRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y',
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.1)',
                                    }
                                },
                                y: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    },
                    genderChart: {
                        type: 'doughnut',
                        data: {
                            labels: ['Hommes', 'Femmes'],
                            datasets: [{
                                data: [0, 0],
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 99, 132, 0.8)'
                                ],
                                borderWidth: 3,
                                borderColor: 'white'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                }
                            }
                        }
                    },
                    ageDistributionChart: {
                        type: 'bar',
                        data: {
                            labels: ['0-18', '19-30', '31-45', '46-60', '60+'],
                            datasets: [{
                                label: 'Patients',
                                data: [0, 0, 0, 0, 0],
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 206, 86, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(153, 102, 255, 0.8)'
                                ],
                                borderWidth: 2,
                                borderColor: 'white',
                                borderRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.1)',
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    },
                    appointmentTypesChart: {
                        type: 'doughnut',
                        data: {
                            labels: [],
                            datasets: [{
                                data: [],
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 206, 86, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(153, 102, 255, 0.8)'
                                ],
                                borderWidth: 3,
                                borderColor: 'white'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                }
                            }
                        }
                    },
                    appointmentStatusChart: {
                        type: 'polarArea',
                        data: {
                            labels: [],
                            datasets: [{
                                data: [],
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(255, 205, 86, 0.8)',
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(153, 102, 255, 0.8)'
                                ],
                                borderWidth: 2,
                                borderColor: 'white'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                }
                            }
                        }
                    },
                    mutuellesChart: {
                        type: 'bar',
                        data: {
                            labels: [],
                            datasets: [{
                                label: 'Patients',
                                data: [],
                                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                                borderColor: 'rgba(16, 185, 129, 1)',
                                borderWidth: 2,
                                borderRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.1)',
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    },
                    chronicConditionsChart: {
                        type: 'pie',
                        data: {
                            labels: [],
                            datasets: [{
                                data: [],
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(255, 205, 86, 0.8)',
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(153, 102, 255, 0.8)'
                                ],
                                borderWidth: 2,
                                borderColor: 'white'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                }
                            }
                        }
                    }
                };

                // Créer ou mettre à jour les graphiques
                Object.keys(chartConfigs).forEach(chartId => {
                    const canvas = document.getElementById(chartId);
                    if (!canvas) return;
                    
                    if (charts[chartId]) {
                        charts[chartId].destroy();
                    }
                    
                    charts[chartId] = new Chart(
                        canvas.getContext('2d'),
                        chartConfigs[chartId]
                    );
                });
            }

            // Charger les données du rapport
            function loadReportData() {
                showLoading(true);
                hideError();
                
                const url = `/rapports/data?year=${currentYear}&mode=${currentMode}&month=${currentMonth}`;
                
                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Erreur HTTP: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {
                            throw new Error(data.message);
                        }
                        updateCharts(data);
                        updatePeriodIndicator();
                        showLoading(false);
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                        showError(error.message);
                        showLoading(false);
                    });
            }

            // Mettre à jour les graphiques avec les nouvelles données
            // Remplacer la fonction updateCharts dans le script JavaScript
function updateCharts(data) {
    console.log('Données reçues:', data); // Pour débugger
    
    // Effet de transition
    document.getElementById('charts-container').classList.add('fade-out');
    
    setTimeout(() => {
        // Revenus par mois/jour
        if (charts.incomeChart && data.incomeData) {
            console.log('Mise à jour du graphique des revenus:', data.incomeData);
            charts.incomeChart.data.labels = data.incomeData.labels || [];
            charts.incomeChart.data.datasets[0].data = data.incomeData.data || [];
            charts.incomeChart.update();
        }
        
        // Rendez-vous par mois/jour
        if (charts.appointmentsChart && data.appointmentsData) {
            console.log('Mise à jour du graphique des rendez-vous:', data.appointmentsData);
            charts.appointmentsChart.data.labels = data.appointmentsData.labels || [];
            charts.appointmentsChart.data.datasets[0].data = data.appointmentsData.data || [];
            charts.appointmentsChart.update();
        }

        // Répartition des analyses
        if (charts.analysisDistributionChart && data.analysisTypes) {
            charts.analysisDistributionChart.data.labels = data.analysisTypes.labels || [];
            charts.analysisDistributionChart.data.datasets[0].data = data.analysisTypes.data || [];
            charts.analysisDistributionChart.update();
        }

        // Médicaments les plus prescrits
        if (charts.medicationsChart && data.medications) {
            charts.medicationsChart.data.labels = data.medications.labels || [];
            charts.medicationsChart.data.datasets[0].data = data.medications.data || [];
            charts.medicationsChart.update();
        }

        // Répartition par genre
        if (charts.genderChart && data.genderDistribution) {
            charts.genderChart.data.datasets[0].data = [
                data.genderDistribution.male || 0,
                data.genderDistribution.female || 0
            ];
            charts.genderChart.update();
        }

        // Répartition par âge
        if (charts.ageDistributionChart && data.ageDistribution) {
            charts.ageDistributionChart.data.labels = data.ageDistribution.labels || ['0-18', '19-30', '31-45', '46-60', '60+'];
            charts.ageDistributionChart.data.datasets[0].data = data.ageDistribution.data || [0, 0, 0, 0, 0];
            charts.ageDistributionChart.update();
        }

        // Types de rendez-vous
        if (charts.appointmentTypesChart && data.appointmentTypes) {
            charts.appointmentTypesChart.data.labels = data.appointmentTypes.labels || [];
            charts.appointmentTypesChart.data.datasets[0].data = data.appointmentTypes.data || [];
            charts.appointmentTypesChart.update();
        }

        // Statut des rendez-vous
        if (charts.appointmentStatusChart && data.appointmentStatus) {
            charts.appointmentStatusChart.data.labels = data.appointmentStatus.labels || [];
            charts.appointmentStatusChart.data.datasets[0].data = data.appointmentStatus.data || [];
            charts.appointmentStatusChart.update();
        }

        // Distribution des mutuelles
        if (charts.mutuellesChart && data.mutuelles) {
            charts.mutuellesChart.data.labels = data.mutuelles.labels || [];
            charts.mutuellesChart.data.datasets[0].data = data.mutuelles.data || [];
            charts.mutuellesChart.update();
        }

        // Conditions chroniques
        if (charts.chronicConditionsChart && data.chronicConditions) {
            charts.chronicConditionsChart.data.labels = data.chronicConditions.labels || [];
            charts.chronicConditionsChart.data.datasets[0].data = data.chronicConditions.data || [];
            charts.chronicConditionsChart.update();
        }

        // Mettre à jour les titres des graphiques selon le mode
        const incomeTitle = data.mode === 'month' ? 'Revenus par Jour (DH)' : 'Revenus par Mois (DH)';
        const appointmentsTitle = data.mode === 'month' ? 'Rendez-vous par Jour' : 'Rendez-vous par Mois';
        
        document.getElementById('income-chart-title').textContent = incomeTitle;
        document.getElementById('appointments-chart-title').textContent = appointmentsTitle;

        document.getElementById('charts-container').classList.remove('fade-out');
    }, 200);
}
            function showLoading(show) {
                document.getElementById('loading-indicator').classList.toggle('hidden', !show);
            }

            function showError(message) {
                document.getElementById('error-display').classList.remove('hidden');
                document.getElementById('error-message').textContent = message;
            }

            function hideError() {
                document.getElementById('error-display').classList.add('hidden');
                document.getElementById('error-message').textContent = '';
            }

            function updatePeriodIndicator() {
                const text = currentMode === 'year'
                    ? `Données pour l'année ${currentYear}`
                    : `Données pour ${getMonthName(currentMonth)} ${currentYear}`;
                document.getElementById('period-text').textContent = text;
            }

            // Changer d’année
            document.getElementById('prev-year').addEventListener('click', () => {
                currentYear--;
                document.getElementById('current-year-display').textContent = currentYear;
                updatePeriodIndicator();
                loadReportData();
            });

            document.getElementById('next-year').addEventListener('click', () => {
                currentYear++;
                document.getElementById('current-year-display').textContent = currentYear;
                updatePeriodIndicator();
                loadReportData();
            });

            // Changer de mois
            document.getElementById('month-selector').addEventListener('change', (e) => {
                currentMonth = parseInt(e.target.value);
                updatePeriodIndicator();
                loadReportData();
            });

            // Changer de mode
            document.querySelectorAll('.mode-option').forEach(option => {
                option.addEventListener('click', function () {
                    document.querySelectorAll('.mode-option').forEach(el => el.classList.remove('active'));
                    this.classList.add('active');
                    currentMode = this.dataset.mode;

                    document.getElementById('month-controls').classList.toggle('hidden', currentMode === 'year');
                    updatePeriodIndicator();
                    loadReportData();
                });
            });

            // Bouton PDF
            document.getElementById('generate-pdf').addEventListener('click', () => {
                const url = `/rapports/pdf?year=${currentYear}&mode=${currentMode}&month=${currentMonth}`;
                window.open(url, '_blank');
            });

            initCharts();
            loadReportData();
        });
    </script>
</body>
</html>
