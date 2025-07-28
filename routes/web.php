<?php

use App\Http\Controllers\RapportsController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\MedicamentController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\DeconnectionController;

Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Route par défaut corrigée - redirige vers la date d'aujourd'hui
    Route::get('/', function () {
        $today = Carbon::now()->format('Y-m-d');
        return redirect()->route('appointments.index', ['date' => $today]);
    })->name('home');

    // Route principale pour les rendez-vous avec date
    Route::get('/appointments/{date}', [AppointmentController::class, 'index'])->name('appointments.index');

    // Route alternative sans paramètre - redirige vers aujourd'hui
    Route::get('/appointments', function () {
        $today = Carbon::now()->format('Y-m-d');
        return redirect()->route('appointments.index', ['date' => $today]);
    });

    Route::get('/medecin', [MedecinController::class, 'dashboard'])->name('medecin.dashboard');
    
    // Routes pour les mises à jour de statut
    Route::post('/appointments/update-status', [MedecinController::class, 'updateStatus'])->name('appointments.update_status');
    Route::post('/appointments/navigate-patient', [MedecinController::class, 'navigatePatient'])->name('appointments.navigate_patient');
    Route::post('/appointments/return-to-consultation', [MedecinController::class, 'returnToConsultation'])->name('appointments.return-to-consultation');

    Route::get('/check-consultation-availability', [MedecinController::class, 'checkConsultationAvailability'])->name('medecin.check-consultation');

    Route::get('/appointments/{id}/details', [AppointmentController::class, 'showEditForm'])->name('appointments.details');

    // Mise à jour du statut des rendez-vous
    Route::post('/appointments/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    
    // Compteur mensuel des rendez-vous
    Route::get('/appointments-count/{yearMonth}', [AppointmentController::class, 'getMonthlyCounts'])->name('appointments.count');

    // Routes pour les patients
    Route::get('/patients/list', [PatientController::class, 'getPatientsList'])->name('patients.list');
    Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');
    Route::get('/patients/modal', [PatientController::class, 'modal'])->name('patients.modal');
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::put('/patients/{id}', [PatientController::class, 'update'])->name('patients.update');
    Route::get('/patient-details/{id}', [PatientController::class, 'show'])->name('patient.details');
    Route::put('/patient-details/{id}', [PatientController::class, 'updatee'])->name('patients.updatee');
    Route::post('/patients/{id}/archive', [PatientController::class, 'archive'])->name('patients.archive');

    // Routes pour les rendez-vous
    Route::post('/appointments/create', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    
    // Routes pour mutuelle
    Route::post('/appointments/toggle-mutuelle-ajax', [AppointmentController::class, 'toggleMutuelleAjax'])->name('appointments.toggle-mutuelle.ajax');
    Route::post('/appointments/toggle-mutuelle', [AppointmentController::class, 'toggleMutuelle'])->name('appointments.toggle-mutuelle');

    // Routes pour l'édition des rendez-vous
    Route::post('/appointments/edit/{id}', [AppointmentController::class, 'editAppointmentDetails'])->name('appointments.edit');
    Route::get('/appointments/edit/{id}', [AppointmentController::class, 'showEditForm'])->name('appointments.edit.form');
    
    // Routes de recherche
    Route::get('/medicaments/search', [AppointmentController::class, 'searchMedicaments'])->name('medicaments.search');
    Route::get('/analyses/search', [AppointmentController::class, 'searchAnalyses'])->name('analyses.search');
    
    // Routes pour les médicaments
    Route::get('/medicaments', [MedicamentController::class, 'index'])->name('medicaments.index');
    Route::post('/medicaments', [MedicamentController::class, 'store'])->name('medicaments.store');
    Route::put('/medicaments/{id}', [MedicamentController::class, 'update'])->name('medicaments.update');
    Route::delete('/medicaments/{id}', [MedicamentController::class, 'destroy'])->name('medicaments.destroy');
    Route::put('/medicaments/restore/{id}', [MedicamentController::class, 'restore'])->name('medicaments.restore');
    Route::put('/medicaments/archive/{id}', [MedicamentController::class, 'archive'])->name('medicaments.archive');

    // Mise à jour des médicaments pour un rendez-vous
    Route::post('/appointments/{id}/medications/update', [AppointmentController::class, 'updateMedications']);

    // Routes pour les analyses
    Route::get('/analyses', [AnalysisController::class, 'index'])->name('analyses.index');
    Route::post('/analyses', [AnalysisController::class, 'store'])->name('analyses.store');
    Route::put('/analyses/{id}', [AnalysisController::class, 'update'])->name('analyses.update');
    Route::delete('/analyses/{id}', [AnalysisController::class, 'destroy'])->name('analyses.destroy');
    Route::put('/analyses/{id}/archive', [AnalysisController::class, 'archive'])->name('analysis.archive');
    Route::put('/analyses/{id}/restore', [AnalysisController::class, 'restore'])->name('analysis.restore');

    // Routes pour les rapports et paramètres
    Route::get('/rapports', fn() => view('rapports'));
    Route::get('/settings', fn() => view('settings'));
});

// Routes pour les rapports (en dehors du middleware auth si nécessaire)
Route::prefix('rapports')->group(function () {
    Route::get('/', [RapportsController::class, 'index'])->name('rapports.index');
    Route::get('/data', [RapportsController::class, 'getData'])->name('rapports.data');
    Route::get('/pdf', [RapportsController::class, 'generatePDF'])->name('rapports.pdf');
});

// Route pour la déconnexion
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
// 

// Route pour récupérer les rendez-vous par date
Route::get('/medecin/rendez-vous/{date}', [MedecinController::class, 'getAppointmentsByDate']);