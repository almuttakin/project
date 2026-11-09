<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BridgeController;
use App\Http\Controllers\ChartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Publik: Home (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autentikasi Admin TI (Login & Logout)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Seluruh Route Dashboard & Monitoring dan Transaksi & XML Engine (Dilindungi Middleware Auth)
Route::middleware('auth')->group(function () {
    // Dashboard & Monitoring
    Route::get('/dashboard', [BridgeController::class, 'index'])->name('bridge.index');
    Route::get('/dashboard/home', [BridgeController::class, 'index'])->name('dashboard');

    // Transaksi & XML Engine / SAP Integration
    Route::post('/store', [BridgeController::class, 'store'])->name('bridge.store');
    Route::get('/delete/{id}', [BridgeController::class, 'destroy'])->name('bridge.delete');
    Route::get('/generate-xml/{id?}', [BridgeController::class, 'generateXml'])->name('bridge.xml');
    Route::post('/push-sap/{id}', [BridgeController::class, 'pushSap'])->name('bridge.push');
    Route::post('/push-sap-all', [BridgeController::class, 'pushAllSap'])->name('bridge.pushAll');
    Route::get('/xml-preview/{id?}', [BridgeController::class, 'xmlPreview'])->name('bridge.xmlPreview');
});
