<?php

use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleDocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/vehicles', [VehicleController::class, 'index']);
Route::post('/vehicles', [VehicleController::class, 'store']);
Route::get('/vehicles-data', [VehicleController::class, 'getVehiclesData']);
Route::post('/vehicles/{id}', [VehicleController::class, 'update']);
Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy']);

Route::get('/maintenances', [MaintenanceController::class, 'index']);
Route::post('/change-vehicle-status/{id}', [MaintenanceController::class, 'changeVehicleStatus']);
Route::get('/maintenances-data', [MaintenanceController::class, 'getMaintenancesData']);
Route::get('/vehicles-data-for-maintenance', [MaintenanceController::class, 'getVehicleDataForMaintenance']);
Route::post('/maintenances', [MaintenanceController::class, 'store']);
Route::post('/maintenances/{id}', [MaintenanceController::class, 'update']);
Route::delete('/maintenances/{id}', [MaintenanceController::class, 'destroy']);

Route::get('/spareparts', [SparepartController::class, 'index']);
Route::get('/suppliers-for-dropdown', [SparepartController::class, 'getSupplierData']);
Route::post('/spareparts', [SparepartController::class, 'store']);
Route::get('/spareparts-data', [SparepartController::class, 'getSparepartsData']);
Route::post('/spareparts/{id}', [SparepartController::class, 'update']);
Route::delete('/spareparts/{id}', [SparepartController::class, 'destroy']);

Route::get('/suppliers', [SupplierController::class, 'index']);
Route::get('/suppliers-data', [SupplierController::class, 'getSupplierData']);
Route::post('/suppliers', [SupplierController::class, 'store']);
Route::post('/suppliers/{id}', [SupplierController::class, 'update']);
Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy']);

Route::get('/documents', [VehicleDocumentController::class, 'index']);
Route::get('/documents-data', [VehicleDocumentController::class, 'getDocumentsData']);
Route::post('/documents', [VehicleDocumentController::class, 'store']);
Route::post('/documents/{id}', [VehicleDocumentController::class, 'update']);
Route::delete('/documents/{id}', [VehicleDocumentController::class, 'destroy']);
Route::get('/vehicles-data-for-document', [VehicleDocumentController::class, 'getVehicleDataForDocument']);
Route::get('documents/download/{filename}', [VehicleDocumentController::class, 'download']);

Route::get('/track', [TrackController::class, 'index']);
