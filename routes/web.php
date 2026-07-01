<?php

use App\Http\Controllers\CatatanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (login, register, dll)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [CatatanController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Catatan (CRUD)
    Route::resource('catatan', CatatanController::class);

    // Import & Export
    Route::post('/catatan/import', [CatatanController::class, 'importExcel'])->name('catatan.import');
    Route::get('/catatan/export/excel', [CatatanController::class, 'exportExcel'])->name('catatan.export.excel');
    Route::get('/catatan/export/pdf', [CatatanController::class, 'exportPdf'])->name('catatan.export.pdf');

});

 