<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripStatus;
use App\Models\Vehicle;
use App\Models\VehicleReport;
use App\Models\VehicleStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        return view('dashboard.index');
    }

    public function getVehicleGroupByStatus()
    {
        $vehicles = Vehicle::select('vehicle_status_id', DB::raw('count(*) as total'))
            ->groupBy('vehicle_status_id')
            ->get();

        $vehicleStatuses = VehicleStatus::all();

        $data = [];

        foreach ($vehicleStatuses as $vehicleStatus) {
            $statusData = [
                'name' => $vehicleStatus->name,
                'total' => 0
            ];

            foreach ($vehicles as $vehicle) {
                if ($vehicle->vehicle_status_id == $vehicleStatus->id) {
                    $statusData['total'] = $vehicle->total;
                    break;
                }
            }

            $data[] = $statusData;
        }

        return response()->json($data);
    }

    public function getTripsGroupByStatus()
    {
        $trips = Trip::select('trip_status_id', DB::raw('count(*) as total'))
            ->groupBy('trip_status_id')
            ->get();

        $tripStatuses = TripStatus::all();

        $data = [];

        foreach ($tripStatuses as $tripStatus) {
            $statusData = [
                'name' => $tripStatus->name,
                'total' => 0
            ];

            foreach ($trips as $trip) {
                if ($trip->trip_status_id == $tripStatus->id) {
                    $statusData['total'] = $trip->total;
                    break;
                }
            }

            $data[] = $statusData;
        }

        return response()->json($data);
    }

    public function getVehicleReportsGroupByFixed()
    {
        $vehicleReports = VehicleReport::select('is_fixed', DB::raw('count(*) as total'))
            ->groupBy('is_fixed')
            ->get();


        return response()->json($vehicleReports);
    }

    public function getMaintenancesGroupByReserviceLevel()
    {
        $maintenances = DB::table('vehicles')
            ->leftJoin('vehicle_maintenances', function ($join) {
                $join->on('vehicles.id', '=', 'vehicle_maintenances.vehicle_id')
                    ->whereRaw('vehicle_maintenances.date = (SELECT MAX(date) FROM vehicle_maintenances WHERE vehicle_id = vehicles.id)');
            })
            ->select(
                DB::raw('SUM(CASE WHEN vehicle_maintenances.date IS NULL OR DATEDIFF(NOW(), vehicle_maintenances.date) <= vehicles.reservice_level THEN 1 ELSE 0 END) as no_service_needed'),
                DB::raw('SUM(CASE WHEN vehicle_maintenances.date IS NOT NULL AND DATEDIFF(NOW(), vehicle_maintenances.date) > vehicles.reservice_level THEN 1 ELSE 0 END) as need_service')
            )
            ->first();
        return response()->json($maintenances);
    }

    public function getSparepartGroupByReorderLevel()
    {
        $spareparts = DB::table('spareparts')
            ->select(
                DB::raw('SUM(CASE WHEN stock <= reorder_level THEN 1 ELSE 0 END) as need_restock'),
                DB::raw('SUM(CASE WHEN stock > reorder_level THEN 1 ELSE 0 END) as no_restock')
            )
            ->first();
        return response()->json($spareparts);
    }

    public function getDocumentGroupByExpiryDate()
    {
        $documents = DB::table('vehicle_documents')
            ->select(
                DB::raw('SUM(CASE WHEN expiry_date >= NOW() THEN 1 ELSE 0 END) as not_expired'),
                DB::raw('SUM(CASE WHEN expiry_date < NOW() THEN 1 ELSE 0 END) as expired')
            )
            ->first();
        return response()->json($documents);
    }
}
