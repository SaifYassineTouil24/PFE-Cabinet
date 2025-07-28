<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titre }}</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3490dc;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .header p {
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 5px;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 8px 15px;
            border-left: 4px solid #3490dc;
            font-size: 18px;
            margin-bottom: 15px;
            color: #2c3e50;
            font-weight: bold;
        }
        .chart-container {
            margin-bottom: 25px;
        }
        .chart-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #3490dc;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #eee;
        }
        .summary-item {
            text-align: center;
            flex: 1;
            padding: 10px;
        }
        .summary-value {
            font-size: 28px;
            font-weight: bold;
            color: #3490dc;
            margin-bottom: 5px;
        }
        .summary-label {
            font-size: 14px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13px;
        }
        th {
            background-color: #3490dc;
            color: white;
            text-align: left;
            padding: 10px;
            font-weight: bold;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .two-columns {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .column {
            flex: 1;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #7f8c8d;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            background-color: #f0f0f0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titre }}</h1>
        <p>Période du {{ $startDate }} au {{ $endDate }}</p>
    </div>
<!--
    <div class="summary">
        <div class="summary-item">
            <div class="summary-value">{{ $totalAppointments }}</div>
            <div class="summary-label">Total Rendez-vous</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">{{ number_format($totalIncome, 2) }} DH</div>
            <div class="summary-label">Revenus Totaux</div>
        </div>-->
        <div class="summary-item">
            <div class="summary-value">{{ $genderDistribution['male'] + $genderDistribution['female'] }}</div>
            <div class="summary-label">Patients Uniques</div>
        </div>
    </div>

    <!-- Section Revenus et Rendez-vous 
    <div class="section">
        <div class="section-title">
            <i class="fas fa-chart-bar"></i> Statistiques Mensuelles
        </div>
        
        <div class="chart-container">
            <div class="chart-title">Revenus par mois (DH)</div>
            <table>
                <thead>
                    <tr>
                        @foreach($incomeData['labels'] as $month)
                            <th>{{ $month }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($incomeData['data'] as $amount)
                            <td style="text-align: right">{{ number_format($amount, 2) }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="chart-container">
            <div class="chart-title">Nombre de rendez-vous par mois</div>
            <table>
                <thead>
                    <tr>
                        @foreach($appointmentsData['labels'] as $month)
                            <th>{{ $month }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($appointmentsData['data'] as $count)
                            <td style="text-align: center">{{ $count }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>-->

    <!-- Section Patients -->
    <div class="section">
        <div class="section-title">
            <i class="fas fa-user-friends"></i> Démographie des Patients
        </div>
        
        <div class="two-columns">
            <div class="column">
                <div class="chart-title">Répartition par âge</div>
                <table>
                    <thead>
                        <tr>
                            <th>Tranche d'âge</th>
                            <th>Nombre</th>
                            <th>Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPatients = array_sum($ageDistribution['data']);
                        @endphp
                        @foreach($ageDistribution['labels'] as $index => $ageGroup)
                            <tr>
                                <td>{{ $ageGroup }}</td>
                                <td style="text-align: center">{{ $ageDistribution['data'][$index] }}</td>
                                <td style="text-align: center">
                                    @if($totalPatients > 0)
                                        {{ round(($ageDistribution['data'][$index] / $totalPatients) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="column">
                <div class="chart-title">Répartition par genre</div>
                <table>
                    <tr>
                        <th>Genre</th>
                        <th>Nombre</th>
                        <th>Pourcentage</th>
                    </tr>
                    @php
                        $totalGender = $genderDistribution['male'] + $genderDistribution['female'];
                    @endphp
                    <tr>
                        <td>Hommes</td>
                        <td style="text-align: center">{{ $genderDistribution['male'] }}</td>
                        <td style="text-align: center">
                            @if($totalGender > 0)
                                {{ round(($genderDistribution['male'] / $totalGender) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Femmes</td>
                        <td style="text-align: center">{{ $genderDistribution['female'] }}</td>
                        <td style="text-align: center">
                            @if($totalGender > 0)
                                {{ round(($genderDistribution['female'] / $totalGender) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Section Analyses et Médicaments -->
    <div class="section">
        <div class="section-title">
            <i class="fas fa-flask"></i> Analyses et Prescriptions
        </div>
        
        <div class="two-columns">
            <div class="column">
                <div class="chart-title">Top 5 des analyses</div>
                <table>
                    <thead>
                        <tr>
                            <th>Type d'analyse</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($analysisTypes['labels'] as $index => $label)
                            @if($index < 5)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td style="text-align: center">{{ $analysisTypes['data'][$index] }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="column">
                <div class="chart-title">Top 5 des médicaments prescrits</div>
                <table>
                    <thead>
                        <tr>
                            <th>Médicament</th>
                            <th>Prescriptions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medications['labels'] as $index => $label)
                            @if($index < 5)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td style="text-align: center">{{ $medications['data'][$index] }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Section Rendez-vous -->
    <div class="section">
        <div class="section-title">
            <i class="fas fa-calendar-alt"></i> Statistiques des Rendez-vous
        </div>
        
        <div class="two-columns">
            <div class="column">
                <div class="chart-title">Types de rendez-vous</div>
                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Nombre</th>
                            <th>Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAppointmentTypes = array_sum($appointmentTypes['data']);
                        @endphp
                        @foreach($appointmentTypes['labels'] as $index => $label)
                            <tr>
                                <td>{{ $label }}</td>
                                <td style="text-align: center">{{ $appointmentTypes['data'][$index] }}</td>
                                <td style="text-align: center">
                                    @if($totalAppointmentTypes > 0)
                                        {{ round(($appointmentTypes['data'][$index] / $totalAppointmentTypes) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="column">
                <div class="chart-title">Statut des rendez-vous</div>
                <table>
                    <thead>
                        <tr>
                            <th>Statut</th>
                            <th>Nombre</th>
                            <th>Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAppointmentStatus = array_sum($appointmentStatus['data']);
                        @endphp
                        @foreach($appointmentStatus['labels'] as $index => $label)
                            <tr>
                                <td>{{ $label }}</td>
                                <td style="text-align: center">{{ $appointmentStatus['data'][$index] }}</td>
                                <td style="text-align: center">
                                    @if($totalAppointmentStatus > 0)
                                        {{ round(($appointmentStatus['data'][$index] / $totalAppointmentStatus) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Section Mutuelles et Conditions -->
    <div class="section">
        <div class="section-title">
            <i class="fas fa-heartbeat"></i> Mutuelles et Conditions de Santé
        </div>
        
        <div class="two-columns">
            <div class="column">
                <div class="chart-title">Top 5 des mutuelles</div>
                <table>
                    <thead>
                        <tr>
                            <th>Mutuelle</th>
                            <th>Patients</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mutuelles['labels'] as $index => $label)
                            @if($index < 5)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td style="text-align: center">{{ $mutuelles['data'][$index] }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="column">
                <div class="chart-title">Top 5 des conditions chroniques</div>
                <table>
                    <thead>
                        <tr>
                            <th>Condition</th>
                            <th>Patients</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chronicConditions['labels'] as $index => $label)
                            @if($index < 5)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td style="text-align: center">{{ $chronicConditions['data'][$index] }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="footer">
        Rapport généré le {{ now()->format('d/m/Y à H:i') }} | Clinique Propre
    </div>
</body>
</html>