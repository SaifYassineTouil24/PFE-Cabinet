<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;


Route::get('/', [AppointmentController::class, 'index']);
Route::post('/appointments/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');

Route::get('/login', function () {
    return view('login');
});


Route::get('/patients', function () {
    return view('patients');
});

Route::get('/patient-details', function () {
    return view('patient-details');
});

Route::get('/medicaments', function () {
    return view('medicaments');
});

Route::get('/analyses', function () {
    return view('analyses');
});

Route::get('/rapports', function () {
    return view('rapports');
});

Route::get('/settings', function () {
    return view('settings');
});
