<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\BridgeController;

// Ubah '/dashboard' menjadi '/'
Route::get('/', [ChartController::class, 'index']);
Route::get('/', [BridgeController::class, 'index'])->name('bridge.index');
Route::post('/store', [BridgeController::class, 'store'])->name('bridge.store');
Route::get('/delete/{id}', [BridgeController::class, 'destroy'])->name('bridge.delete');
Route::get('/generate-xml/{id}', [BridgeController::class, 'generateXml'])->name('bridge.xml');