<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleDocumentController;
use App\Http\Controllers\VehicleReportController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'store']);
});

Route::get('/', function () {
    return redirect('/track');
});

Route::group(['middleware' => ['auth', 'is_active']], function () {
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::get('/logout', [AuthController::class, 'destroy']);

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

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users-data', [UserController::class, 'getUsersData']);
    Route::post('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/trips', [TripController::class, 'index']);
    Route::post('/trips', [TripController::class, 'store']);
    Route::get('/trips-data', [TripController::class, 'getTripsData']);
    Route::post('/trips/{id}', [TripController::class, 'update']);
    Route::delete('/trips/{id}', [TripController::class, 'destroy']);
    Route::get('/drivers-for-dropdown', [TripController::class, 'getDriverData']);
    Route::get('/vehicles-for-dropdown', [TripController::class, 'getVehicleData']);
    Route::get('/live-location/{id}', [TripController::class, 'getLiveLocation']);

    Route::get('/track', [TrackController::class, 'index']);
    Route::get('/get-track-for-driver', [TrackController::class, 'getTrackForDriver']);
    Route::post('/report-vehicle-for-driver/{id}', [TrackController::class, 'reportVehicleForDriver']);
    Route::post('/report-trip-for-driver/{id}', [TrackController::class, 'reportTripForDriver']);
    Route::post('/start-tracking-for-driver/{id}', [TrackController::class, 'startTrackingForDriver']);
    Route::post('/finish-tracking-for-driver/{id}', [TrackController::class, 'finishTrackingForDriver']);
    Route::get('/track-history-for-driver', [TrackController::class, 'trackHistoryForDriver']);
    Route::post('/update-location/{id}', [TrackController::class, 'updateLocation']);

    Route::get('/vehicle-reports', [VehicleReportController::class, 'index']);
    Route::get('/vehicles-data-for-report', [VehicleReportController::class, 'getVehicleDataForReport']);
    Route::get('/report-details-data', [VehicleReportController::class, 'getReportDetailsData']);
    Route::post('/mark-as-fixed/{id}', [VehicleReportController::class, 'markAsFixed']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/get-vehicle-group-by-status', [DashboardController::class, 'getVehicleGroupByStatus']);
    Route::get('/get-trips-group-by-status', [DashboardController::class, 'getTripsGroupByStatus']);
    Route::get('/get-vehicle-reports-group-by-fixed', [DashboardController::class, 'getVehicleReportsGroupByFixed']);
    Route::get('/get-maintenances-group-by-reservice-level', [DashboardController::class, 'getMaintenancesGroupByReserviceLevel']);
    Route::get('get-spareparts-group-by-reorder-level', [DashboardController::class, 'getSparepartGroupByReorderLevel']);
    Route::get('/get-documents-group-by-expiry-date', [DashboardController::class, 'getDocumentGroupByExpiryDate']);

    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::get('/roles-data', [RoleController::class, 'getRolesData']);
    Route::post('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
});
