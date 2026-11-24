<?php

<<<<<<< HEAD
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Mendaftarkan rute halaman publik dan rute aplikasi berdasarkan peran pengguna.
// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Owner Routes
// Membatasi seluruh rute laporan pemilik hanya untuk pengguna berperan owner.
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/rekap', [OwnerController::class, 'rekap'])->name('rekap');
    Route::get('/rekap/filter', [OwnerController::class, 'rekapFilter'])->name('rekap.filter');
});

// Admin Routes
// Mengelompokkan pengelolaan data dan akun yang hanya boleh diakses admin.
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/tarif', [TarifController::class, 'index'])->name('tarif');
    Route::get('/area', [AreaController::class, 'index'])->name('area');
    Route::get('/kendaraan', [KendaraanController::class, 'index'])->name('kendaraan');
    Route::get('/log-aktivitas', [LogController::class, 'index'])->name('log-aktivitas');
    Route::get('/pengaturan-akun', [AccountController::class, 'edit'])->name('account.edit');
    Route::patch('/pengaturan-akun', [AccountController::class, 'update'])->name('account.update');
    Route::resource('users', UserController::class);
    Route::resource('tarifs', TarifController::class);
    Route::resource('areas', AreaController::class);
    Route::resource('kendaraans', KendaraanController::class);
    Route::get('/logs', [LogController::class, 'index'])->name('logs');
    Route::delete('/logs/{id}', [LogController::class, 'destroy'])->name('logs.destroy');
    Route::post('/logs/clear', [LogController::class, 'clear'])->name('logs.clear');
});

// Petugas Routes
// Mengelompokkan alur transaksi parkir yang hanya boleh diakses petugas.
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
    Route::get('/transaksi', [PetugasController::class, 'transaksi'])->name('transaksi');
    Route::post('/masuk', [PetugasController::class, 'kendaraanMasuk'])->name('masuk');
    Route::get('/keluar', [PetugasController::class, 'kendaraanKeluar'])->name('keluar');
    Route::post('/keluar', [PetugasController::class, 'kendaraanKeluar'])->name('keluar.proses');
    Route::get('/struk/{id}', [PetugasController::class, 'cetakStruk'])->name('struk');
});
=======
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JokeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/joke', [JokeController::class, 'index'])->name('joke.index');
Route::post('/joke/fetch', [JokeController::class, 'fetchJoke'])->name('joke.fetch');
>>>>>>> 2719a8768e6bc6c45f29cf2c011aecb1c6686891
