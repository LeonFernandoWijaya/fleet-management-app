<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripStatus;
use App\Models\Vehicle;
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
}
