<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Appointment;
use App\Models\Analysis;
use App\Models\Medicament;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class RapportsController extends Controller
{
    public function index()
    {
        return view('rapports');
    }

    public function getData(Request $request)
    {
        try {
            $year = $request->input('year', date('Y'));
            $mode = $request->input('mode', 'year'); // 'year' ou 'month'
            $month = $request->input('month', date('n')); // mois actuel par défaut
            
            // Calculer la plage de dates selon le mode
            if ($mode === 'month') {
                $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
                $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
            } else {
                $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
                $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
            }
            
            return response()->json([
                'incomeData' => $this->getIncomeData($startDate, $endDate, $mode),
                'appointmentsData' => $this->getAppointmentsData($startDate, $endDate, $mode),
                'analysisTypes' => $this->getAnalysisTypeDistribution($startDate, $endDate),
                'medications' => $this->getTopMedications($startDate, $endDate),
                'genderDistribution' => $this->getGenderDistribution($startDate, $endDate),
                'ageDistribution' => $this->getAgeDistribution($startDate, $endDate),
                'mutuelles' => $this->getMutuellesDistribution($startDate, $endDate),
                'chronicConditions' => $this->getChronicConditions($startDate, $endDate),
                'appointmentTypes' => $this->getAppointmentTypes($startDate, $endDate),
                'appointmentStatus' => $this->getAppointmentStatus($startDate, $endDate),
                'mode' => $mode,
                'year' => $year,
                'month' => $month
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur dans getData: " . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Erreur lors du chargement des données: ' . $e->getMessage()
            ], 500);
        }
    }

private function getIncomeData($startDate, $endDate, $mode)
{
    try {
        if ($mode === 'month') {
            // Mode mensuel - grouper par jour
            $query = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
                ->whereNotNull('payement')
                ->where('payement', '>', 0) // Prendre tous les paiements > 0
                ->select(
                    DB::raw('COALESCE(SUM(payement), 0) as total'),
                    DB::raw('DAY(appointment_date) as day')
                )
                ->groupBy(DB::raw('DAY(appointment_date)'))
                ->orderBy('day');

            $results = $query->get();
            $daysInMonth = $endDate->day;

            // Créer un tableau avec tous les jours du mois
            $allDays = [];
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $allDays[$i] = 0;
            }

            // Remplir avec les données existantes
            foreach ($results as $result) {
                $allDays[$result->day] = floatval($result->total);
            }

            // Créer les labels pour les jours
            $labels = [];
            $data = [];
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $labels[] = (string)$i;
                $data[] = $allDays[$i];
            }

            return [
                'labels' => $labels,
                'data' => $data
            ];
        } else {
            // Mode annuel - grouper par mois
            $query = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
                ->whereNotNull('payement')
                ->where('payement', '>', 0) // Prendre tous les paiements > 0
                ->select(
                    DB::raw('COALESCE(SUM(payement), 0) as total'),
                    DB::raw('MONTH(appointment_date) as month')
                )
                ->groupBy(DB::raw('MONTH(appointment_date)'))
                ->orderBy('month');

            $results = $query->get();

            // Créer un tableau avec tous les mois
            $allMonths = [];
            for ($i = 1; $i <= 12; $i++) {
                $allMonths[$i] = 0;
            }

            // Remplir avec les données existantes
            foreach ($results as $result) {
                $allMonths[$result->month] = floatval($result->total);
            }

            $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
            $data = [];
            
            for ($i = 1; $i <= 12; $i++) {
                $data[] = $allMonths[$i];
            }

            return [
                'labels' => $months,
                'data' => $data
            ];
        }
    } catch (\Exception $e) {
        Log::error("Erreur dans getIncomeData: " . $e->getMessage());
        return [
            'labels' => $mode === 'month' ? [] : ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            'data' => $mode === 'month' ? [] : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        ];
    }
}

private function getAppointmentsData($startDate, $endDate, $mode)
{
    try {
        if ($mode === 'month') {
            // Mode mensuel - grouper par jour
            $query = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
                ->select(
                    DB::raw('COUNT(*) as count'),
                    DB::raw('DAY(appointment_date) as day')
                )
                ->groupBy(DB::raw('DAY(appointment_date)'))
                ->orderBy('day');

            $results = $query->get();
            $daysInMonth = $endDate->day;

            // Créer un tableau avec tous les jours du mois
            $allDays = [];
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $allDays[$i] = 0;
            }

            // Remplir avec les données existantes
            foreach ($results as $result) {
                $allDays[$result->day] = intval($result->count);
            }

            // Créer les labels pour les jours
            $labels = [];
            $data = [];
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $labels[] = (string)$i;
                $data[] = $allDays[$i];
            }

            return [
                'labels' => $labels,
                'data' => $data
            ];
        } else {
            // Mode annuel - grouper par mois
            $query = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
                ->select(
                    DB::raw('COUNT(*) as count'),
                    DB::raw('MONTH(appointment_date) as month')
                )
                ->groupBy(DB::raw('MONTH(appointment_date)'))
                ->orderBy('month');

            $results = $query->get();

            // Créer un tableau avec tous les mois
            $allMonths = [];
            for ($i = 1; $i <= 12; $i++) {
                $allMonths[$i] = 0;
            }

            // Remplir avec les données existantes
            foreach ($results as $result) {
                $allMonths[$result->month] = intval($result->count);
            }

            $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
            $data = [];
            
            for ($i = 1; $i <= 12; $i++) {
                $data[] = $allMonths[$i];
            }

            return [
                'labels' => $months,
                'data' => $data
            ];
        }
    } catch (\Exception $e) {
        Log::error("Erreur dans getAppointmentsData: " . $e->getMessage());
        return [
            'labels' => $mode === 'month' ? [] : ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            'data' => $mode === 'month' ? [] : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        ];
    }
}
    private function getDateSelect($dateRange)
    {
        switch ($dateRange) {
            case 'month':
                return DB::raw('DAY(appointment_date) as date_group');
            case 'quarter':
            case 'semester':
                return DB::raw('MONTH(appointment_date) as date_group');
            default: // year
                return DB::raw('MONTH(appointment_date) as date_group');
        }
    }

    private function getDateGroup($dateRange)
    {
        switch ($dateRange) {
            case 'month':
                return DB::raw('DAY(appointment_date)');
            case 'quarter':
            case 'semester':
                return DB::raw('MONTH(appointment_date)');
            default: // year
                return DB::raw('MONTH(appointment_date)');
        }
    }

    private function formatLabel($value, $dateRange)
    {
        if ($dateRange === 'month') {
            return 'Jour ' . $value;
        } elseif ($dateRange === 'year') {
            $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
            return $months[$value - 1] ?? $value;
        } else {
            $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            return $months[$value - 1] ?? $value;
        }
    }

    private function getAnalysisTypeDistribution($startDate, $endDate)
    {
        try {
            $analysisData = Analysis::join('appointment_analyse', 'analyses.ID_Analyse', '=', 'appointment_analyse.ID_Analyse')
                ->join('appointments', 'appointment_analyse.ID_RV', '=', 'appointments.ID_RV')
                ->whereBetween('appointments.appointment_date', [$startDate, $endDate])
                ->whereNotNull('analyses.type_analyse')
                ->select('analyses.type_analyse', DB::raw('COUNT(*) as count'))
                ->groupBy('analyses.type_analyse')
                ->orderBy('count', 'desc')
                ->limit(6)
                ->get();

            return [
                'labels' => $analysisData->pluck('type_analyse')->toArray(),
                'data' => $analysisData->pluck('count')->toArray()
            ];
        } catch (\Exception $e) {
            Log::error("Erreur dans getAnalysisTypeDistribution: " . $e->getMessage());
            return [
                'labels' => [],
                'data' => []
            ];
        }
    }

    private function getTopMedications($startDate, $endDate)
    {
        try {
            $medicationData = Medicament::join('appointment_medicament', 'medicaments.ID_Medicament', '=', 'appointment_medicament.ID_Medicament')
                ->join('appointments', 'appointment_medicament.ID_RV', '=', 'appointments.ID_RV')
                ->whereBetween('appointments.appointment_date', [$startDate, $endDate])
                ->whereNotNull('medicaments.name')
                ->select('medicaments.name', DB::raw('COUNT(*) as count'))
                ->groupBy('medicaments.name')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get();

            return [
                'labels' => $medicationData->pluck('name')->toArray(),
                'data' => $medicationData->pluck('count')->toArray()
            ];
        } catch (\Exception $e) {
            Log::error("Erreur dans getTopMedications: " . $e->getMessage());
            return [
                'labels' => [],
                'data' => []
            ];
        }
    }

    private function getGenderDistribution($startDate, $endDate)
    {
        try {
            $patients = Patient::join('appointments', 'patients.ID_patient', '=', 'appointments.ID_patient')
                ->whereBetween('appointments.appointment_date', [$startDate, $endDate])
                ->select('patients.gender', DB::raw('COUNT(DISTINCT patients.ID_patient) as count'))
                ->whereIn('patients.gender', ['Male', 'Female'])
                ->groupBy('patients.gender')
                ->get()
                ->pluck('count', 'gender');

            return [
                'male' => $patients->get('Male', 0),
                'female' => $patients->get('Female', 0)
            ];
        } catch (\Exception $e) {
            Log::error("Erreur dans getGenderDistribution: " . $e->getMessage());
            return [
                'male' => 0,
                'female' => 0
            ];
        }
    }

    private function getAgeDistribution($startDate, $endDate)
    {
        try {
            $ageGroups = [
                '0-18' => [0, 18],
                '19-30' => [19, 30],
                '31-45' => [31, 45],
                '46-60' => [46, 60],
                '60+' => [61, 150]
            ];

            $results = array_fill_keys(array_keys($ageGroups), 0);

            $patients = Patient::join('appointments', 'patients.ID_patient', '=', 'appointments.ID_patient')
                ->whereBetween('appointments.appointment_date', [$startDate, $endDate])
                ->select('patients.ID_patient', 'patients.birth_day')
                ->distinct()
                ->get();

            foreach ($patients as $patient) {
                $age = Carbon::parse($patient->birth_day)->age;
                
                foreach ($ageGroups as $group => $range) {
                    if ($age >= $range[0] && $age <= $range[1]) {
                        $results[$group]++;
                        break;
                    }
                }
            }

            return [
                'labels' => array_keys($results),
                'data' => array_values($results)
            ];
        } catch (\Exception $e) {
            Log::error("Erreur dans getAgeDistribution: " . $e->getMessage());
            return [
                'labels' => ['0-18', '19-30', '31-45', '46-60', '60+'],
                'data' => [0, 0, 0, 0, 0]
            ];
        }
    }

    private function getMutuellesDistribution($startDate, $endDate)
    {
        try {
            $mutuelleData = Patient::join('appointments', 'patients.ID_patient', '=', 'appointments.ID_patient')
                ->whereBetween('appointments.appointment_date', [$startDate, $endDate])
                ->whereNotNull('patients.mutuelle')
                ->select('patients.mutuelle', DB::raw('COUNT(DISTINCT patients.ID_patient) as count'))
                ->groupBy('patients.mutuelle')
                ->orderBy('count', 'desc')
                ->limit(6)
                ->get();

            return [
                'labels' => $mutuelleData->pluck('mutuelle')->toArray(),
                'data' => $mutuelleData->pluck('count')->toArray()
            ];
        } catch (\Exception $e) {
            Log::error("Erreur dans getMutuellesDistribution: " . $e->getMessage());
            return [
                'labels' => [],
                'data' => []
            ];
        }
    }

    private function getChronicConditions($startDate, $endDate)
    {
        try {
            $patients = Patient::join('appointments', 'patients.ID_patient', '=', 'appointments.ID_patient')
                ->whereBetween('appointments.appointment_date', [$startDate, $endDate])
                ->whereNotNull('patients.chronic_conditions')
                ->select('patients.chronic_conditions')
                ->distinct()
                ->get();

            $conditionCounts = [];
            foreach ($patients as $patient) {
                $conditions = explode(',', $patient->chronic_conditions);
                foreach ($conditions as $condition) {
                    $condition = trim($condition);
                    if (!empty($condition)) {
                        $conditionCounts[$condition] = ($conditionCounts[$condition] ?? 0) + 1;
                    }
                }
            }

            arsort($conditionCounts);
            $conditionCounts = array_slice($conditionCounts, 0, 5);

            return [
                'labels' => array_keys($conditionCounts),
                'data' => array_values($conditionCounts)
            ];
        } catch (\Exception $e) {
            Log::error("Erreur dans getChronicConditions: " . $e->getMessage());
            return [
                'labels' => [],
                'data' => []
            ];
        }
    }

    private function getAppointmentTypes($startDate, $endDate)
    {
        try {
            $types = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
                ->whereNotNull('type')
                ->select('type', DB::raw('COUNT(*) as count'))
                ->groupBy('type')
                ->orderBy('count', 'desc')
                ->get();

            return [
                'labels' => $types->pluck('type')->toArray(),
                'data' => $types->pluck('count')->toArray()
            ];
        } catch (\Exception $e) {
            Log::error("Erreur dans getAppointmentTypes: " . $e->getMessage());
            return [
                'labels' => [],
                'data' => []
            ];
        }
    }

    private function getAppointmentStatus($startDate, $endDate)
    {
        try {
            $status = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
                ->whereNotNull('status')
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->orderBy('count', 'desc')
                ->get();

            return [
                'labels' => $status->pluck('status')->toArray(),
                'data' => $status->pluck('count')->toArray()
            ];
        } catch (\Exception $e) {
            Log::error("Erreur dans getAppointmentStatus: " . $e->getMessage());
            return [
                'labels' => [],
                'data' => []
            ];
        }
    }

    public function generatePDF(Request $request)
    {
        try {
            $year = $request->input('year', date('Y'));
            $mode = $request->input('mode', 'year');
            $month = $request->input('month', date('n'));
            
            if ($mode === 'month') {
                $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
                $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
            } else {
                $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
                $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
            }
            
            $data = [
                'year' => $year,
                'month' => $month,
                'mode' => $mode,
                'startDate' => $startDate->format('d/m/Y'),
                'endDate' => $endDate->format('d/m/Y'),
                'incomeData' => $this->getIncomeData($startDate, $endDate, $mode),
                'appointmentsData' => $this->getAppointmentsData($startDate, $endDate, $mode),
                'analysisTypes' => $this->getAnalysisTypeDistribution($startDate, $endDate),
                'medications' => $this->getTopMedications($startDate, $endDate),
                'genderDistribution' => $this->getGenderDistribution($startDate, $endDate),
                'ageDistribution' => $this->getAgeDistribution($startDate, $endDate),
                'mutuelles' => $this->getMutuellesDistribution($startDate, $endDate),
                'chronicConditions' => $this->getChronicConditions($startDate, $endDate),
                'appointmentTypes' => $this->getAppointmentTypes($startDate, $endDate),
                'appointmentStatus' => $this->getAppointmentStatus($startDate, $endDate),
                'totalIncome' => array_sum($this->getIncomeData($startDate, $endDate, $mode)['data']),
                'totalAppointments' => array_sum($this->getAppointmentsData($startDate, $endDate, $mode)['data']),
                'titre' => $mode === 'month' 
                    ? "Rapport Statistique - " . $this->getMonthName($month) . " " . $year
                    : "Rapport Statistique - Année " . $year
            ];
            
            $pdf = Pdf::loadView('rapports_pdf', $data);
            
            $filename = $mode === 'month' 
                ? 'rapport_statistique_' . $year . '_' . sprintf('%02d', $month) . '.pdf'
                : 'rapport_statistique_' . $year . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error("Erreur dans generatePDF: " . $e->getMessage());
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    private function getMonthName($monthNumber)
    {
        $months = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];
        return $months[$monthNumber] ?? 'Mois inconnu';
    }
}
