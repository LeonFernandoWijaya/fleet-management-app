<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleStatus;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicleTypes = VehicleType::all();
        $vehicleStatuses = VehicleStatus::all();
        return view('vehicles.index', compact('vehicleTypes', 'vehicleStatuses'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle-type' => 'required|exists:vehicle_types,id',
            'vehicle-status' => 'required|exists:vehicle_statuses,id',
            'vehicle-plate-1' => 'required|string|max:2',
            'vehicle-plate-2' => 'required|integer|digits:4',
            'vehicle-plate-3' => 'required|string|max:2',
            'vehicle-brand' => 'required|string|max:255',
            'vehicle-model' => 'required|string|max:255',
            'vehicle-capacity-ton' => 'required|numeric',
            'vehicle-reservice-level' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $mergedPlate = $request->input('vehicle-plate-1') . '-' . $request->input('vehicle-plate-2') . '-' . $request->input('vehicle-plate-3');

        $findVehicle = Vehicle::where('plate_number', $mergedPlate)->first();
        if ($findVehicle) {
            return response()->json(['errors' => 'Vehicle with the same plate number already exists.'], 422);
        }

        $data = [
            'vehicle_type_id' => $request->input('vehicle-type'),
            'vehicle_status_id' => $request->input('vehicle-status'),
            'plate_number' => $mergedPlate,
            'brand' => $request->input('vehicle-brand'),
            'model' => $request->input('vehicle-model'),
            'capacity_ton' => $request->input('vehicle-capacity-ton'),
            'reservice_level' => $request->input('vehicle-reservice-level'),
        ];

        try {
            Vehicle::createRecord($data);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Something went wrong'], 404);
        }
        return response()->json(['message' => 'Vehicle has been successfully added.'], 200);
    }

    public function getVehiclesData(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');
        $vehicles = Vehicle::with('vehicleType', 'vehicleStatus')
            ->when($filter, function ($query) use ($filter) {
                $query->where('vehicle_status_id', $filter);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('plate_number', 'like', '%' . $search . '%')
                    ->orWhere('brand', 'like', '%' . $search . '%')
                    ->orWhere('model', 'like', '%' . $search . '%');
            })
            ->paginate(10);
        return response()->json($vehicles);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vehicle-type' => 'required|exists:vehicle_types,id',
            'vehicle-status' => 'required|exists:vehicle_statuses,id',
            'vehicle-plate-1' => 'required|string|max:2',
            'vehicle-plate-2' => 'required|integer|digits:4',
            'vehicle-plate-3' => 'required|string|max:2',
            'vehicle-brand' => 'required|string|max:255',
            'vehicle-model' => 'required|string|max:255',
            'vehicle-capacity-ton' => 'required|numeric',
            'vehicle-reservice-level' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $mergedPlate = $request->input('vehicle-plate-1') . '-' . $request->input('vehicle-plate-2') . '-' . $request->input('vehicle-plate-3');

        $findVehicle = Vehicle::where('plate_number', $mergedPlate)->where('id', '!=', $id)->first();

        if ($findVehicle) {
            return response()->json(['errors' => 'Vehicle with the same plate number already exists.'], 422);
        }

        $data = [
            'vehicle_type_id' => $request->input('vehicle-type'),
            'vehicle_status_id' => $request->input('vehicle-status'),
            'plate_number' => $mergedPlate,
            'brand' => $request->input('vehicle-brand'),
            'model' => $request->input('vehicle-model'),
            'capacity_ton' => $request->input('vehicle-capacity-ton'),
            'reservice_level' => $request->input('vehicle-reservice-level'),
        ];

        try {
            Vehicle::updateRecord($id, $data);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Something went wrong'], 404);
        }
        return response()->json(['message' => 'Vehicle has been successfully updated.'], 200);
    }

    public function destroy($id)
    {
        try {
            Vehicle::where('id', $id)->delete();
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Something went wrong'], 404);
        }
        return response()->json(['message' => 'Vehicle has been successfully deleted.'], 200);
    }
}
