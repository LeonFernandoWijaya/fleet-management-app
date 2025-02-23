<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleMaintenance;
use App\Models\VehicleStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class MaintenanceController extends Controller
{
    //
    public function index()
    {
        if (!Gate::allows('moduleAction', ['Maintenance', 'Read'])) {
            abort(403);
        }
        $vehicleStatuses = VehicleStatus::all();
        return view('maintenances.index', compact('vehicleStatuses'));
    }

    public function changeVehicleStatus($id)
    {
        if (!Gate::allows('moduleAction', ['Maintenance', 'Update'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $vehicle = Vehicle::with('vehicleStatus')->find($id);
        $vehicleStatusName = $vehicle->vehicleStatus->name;
        $vehicleStatuses = VehicleStatus::all();

        if ($vehicleStatusName == 'In Use') {
            return response()->json(['errors' => 'vehicle status in use right now'], 404);
        }

        if ($vehicleStatusName == 'On Service') {
            $vehicle->vehicle_status_id =  $vehicleStatuses->where('name', 'Available')->first()->id;
            $vehicle->save();
        } else if ($vehicleStatusName == 'Available') {
            $vehicle->vehicle_status_id =  $vehicleStatuses->where('name', 'On Service')->first()->id;
            $vehicle->save();
        }

        return response()->json(['message' => 'Vehicle status has been successfully changed.'], 200);
    }

    public function getMaintenancesData(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Maintenance', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $vehicleId = $request->input('vehicle_id');
        $vehicleMaintenance = VehicleMaintenance::with('vehicle')->where('vehicle_id', $vehicleId)->orderby('date', 'desc')->paginate(5);
        $canUpdate = Gate::allows('moduleAction', ['Maintenance', 'Update']);
        $canDelete = Gate::allows('moduleAction', ['Maintenance', 'Delete']);

        return response()->json(compact('vehicleMaintenance', 'canUpdate', 'canDelete'));
    }

    public function getVehicleDataForMaintenance(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Maintenance', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $filter = $request->input('filter');
        $search = $request->input('search');

        $vehicles = Vehicle::with(['vehicleType', 'vehicleStatus'])
            ->leftJoin('vehicle_maintenances', function ($join) {
                $join->on('vehicles.id', '=', 'vehicle_maintenances.vehicle_id')
                    ->whereRaw('vehicle_maintenances.date = (SELECT MAX(m2.date) FROM vehicle_maintenances m2 WHERE m2.vehicle_id = vehicles.id)');
            })
            ->when($filter, function ($query) use ($filter) {
                $query->where('vehicle_status_id', $filter);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('plate_number', 'like', '%' . $search . '%');
            })
            ->select('vehicles.*', 'vehicle_maintenances.date as maintenance_date')
            ->selectRaw('CASE WHEN DATEDIFF(CURDATE(), vehicle_maintenances.date) >= vehicles.reservice_level THEN 1 ELSE 0 END AS needs_service')
            ->orderByDesc('needs_service')
            ->paginate(10);

        $canUpdate = Gate::allows('moduleAction', ['Maintenance', 'Update']);
        return response()->json(compact('vehicles', 'canUpdate'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Maintenance', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'maintenance-date' => 'required|date',
            'maintenance-details' => 'required|string|max:255',
            'maintenance-cost' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $data = [
            'vehicle_id' => $request->input('vehicle_id'),
            'date' => new \DateTime($request->input('maintenance-date')),
            'details' => $request->input('maintenance-details'),
            'cost' => $request->input('maintenance-cost'),
        ];
        try {
            VehicleMaintenance::create($data);
            return response()->json(['message' => 'Maintenance has been successfully added.'], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Failed to add maintenance.'], 422);
        }
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('moduleAction', ['Maintenance', 'Update'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'maintenance-date' => 'required|date',
            'maintenance-details' => 'required|string|max:255',
            'maintenance-cost' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $data = [
            'date' => new \DateTime($request->input('maintenance-date')),
            'details' => $request->input('maintenance-details'),
            'cost' => $request->input('maintenance-cost'),
        ];

        try {
            VehicleMaintenance::updateRecord($id, $data);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Failed to update maintenance.'], 422);
        }

        return response()->json(['message' => 'Maintenance has been successfully updated.'], 200);
    }

    public function destroy($id)
    {
        if (!Gate::allows('moduleAction', ['Maintenance', 'Delete'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        try {
            VehicleMaintenance::where('id', $id)->delete();
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Failed to delete maintenance.'], 422);
        }

        return response()->json(['message' => 'Maintenance has been successfully deleted.'], 200);
    }
}
