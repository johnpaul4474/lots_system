<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Controllers
use App\Http\Controllers\LotController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\LotSearchController;
use App\Http\Controllers\ReferencePointController;

// Home & Dashboard
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Include other route files
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

// PSA / Lookup Routes

    Route::get('/regions', [RegionController::class, 'index'])->name('regions.index');
    Route::get('/provinces', [ProvinceController::class, 'index'])->name('provinces.index');
    Route::get('/municipalities', [MunicipalityController::class, 'index'])->name('municipalities.index');
    Route::get('/barangays', [BarangayController::class, 'index'])->name('barangays.index');


// Lot routes
Route::prefix('lots')->group(function () {
    Route::get('/search', [LotSearchController::class, 'search'])->name('lots.search');
    Route::get('/{lot}/ldc', [LotSearchController::class, 'downloadLdc'])->name('lots.downloadLdc');
    Route::get('/{lot}/map', [LotSearchController::class, 'viewMap'])->name('lots.viewMap');
});

// Reference Points routes
Route::prefix('reference-points')->group(function () {
    Route::get('/', [ReferencePointController::class, 'index'])
        ->name('referencePoints');

    Route::get('/search', [ReferencePointController::class, 'search'])
        ->name('reference-points.search');
});