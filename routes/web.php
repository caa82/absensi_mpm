<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ProfileController;

// Redirect home page to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile Settings Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    // Sekretaris Role Routes
    Route::middleware('role:Sekretaris')->prefix('sekretaris')->name('sekretaris.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'sekretarisDashboard'])->name('dashboard');
        
        // Agenda Rapat CRUD
        Route::resource('agenda', AgendaController::class);

        // Notula Routes
        Route::get('/agenda/{id}/notula', [AgendaController::class, 'showNotula'])->name('agenda.notula');
        Route::post('/agenda/{id}/notula', [AgendaController::class, 'storeNotula'])->name('agenda.notula.store');

        // Absensi Management & Recap
        Route::get('/absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
        Route::get('/absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
        Route::get('/absensi/export-pdf', [AbsensiController::class, 'exportPdf'])->name('absensi.export-pdf');
        Route::get('/absensi/detail/{id_agenda}', [AbsensiController::class, 'detail'])->name('absensi.detail');
        Route::post('/absensi/verify-izin/{id_izin}', [AbsensiController::class, 'verifyIzin'])->name('absensi.verify-izin');
    });

    // Anggota Role Routes
    Route::middleware('role:Anggota')->prefix('anggota')->name('anggota.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'anggotaDashboard'])->name('dashboard');
        Route::get('/absensi/isi', [AbsensiController::class, 'create'])->name('absensi.create');
        Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');

        // Daftar Rapat & Detail (Anggota)
        Route::get('/rapat', [AgendaController::class, 'rapatIndex'])->name('rapat.index');
        Route::get('/rapat/{id}', [AgendaController::class, 'rapatShow'])->name('rapat.show');
    });
});
