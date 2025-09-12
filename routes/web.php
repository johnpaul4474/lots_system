<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\LotController;


use App\Http\Controllers\RegionController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\LotSearchController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';



Route::get('/regions', [RegionController::class, 'index']);
Route::get('/provinces', [ProvinceController::class, 'index']);
Route::get('/municipalities', [MunicipalityController::class, 'index']);
Route::get('/barangays', [BarangayController::class, 'index']);
Route::get('/lots/search', [LotSearchController::class, 'search']);
Route::get('/lot_ldc/{lot}', [LotSearchController::class, 'downloadLdc']);
Route::get('/lot_map/{lot}', [LotSearchController::class, 'viewMap']);