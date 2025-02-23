<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripStatus;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class TripController extends Controller
{
    //

    public function index()
    {
        if (!Gate::allows('moduleAction', ['Trip', 'Read'])) {
            abort(403);
        }
        $tripStatuses = TripStatus::all();
        return view('trips.index', compact('tripStatuses'));
    }


    public function getTripsData(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Trip', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $search = $request->search;
        $filter = $request->filter;

        $trips = Trip::with('vehicle', 'user', 'tripStatus')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('vehicle', function ($query) use ($search) {
                    $query->where('plate_number', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            })
            ->when($filter, function ($query) use ($filter) {
                $query->whereHas('tripStatus', function ($query) use ($filter) {
                    $query->where('id', 'like', '%' . $filter . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $canUpdate = Gate::allows('moduleAction', ['Trip', 'Update']);
        $canDelete = Gate::allows('moduleAction', ['Trip', 'Delete']);
        return response()->json(compact('trips', 'canUpdate', 'canDelete'));
    }

    public function getDriverData(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Trip', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $driver = $request->value;
        $drivers = User::with('role')->whereHas('role', function ($query) {
            $query->where('name', 'driver');
        })
            ->when(!$driver, function ($query) {
                $query->where('is_available', 1);
            })
            ->when($driver, function ($query) use ($driver) {
                $query->orWhere('id', $driver);
            })
            ->get();
        return response()->json($drivers);
    }

    public function getVehicleData(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Trip', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $vehicle = $request->value;
        $vehicles = Vehicle::with('vehicleStatus', 'vehicleType')->whereHas('vehicleStatus', function ($query) {
            $query->where('name', 'available');
        })
            ->when($vehicle, function ($query) use ($vehicle) {
                $query->orWhere('id', $vehicle);
            })
            ->get();
        return response()->json($vehicles);
    }

    public function store(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Trip', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'trip-vehicle' => 'required|exists:vehicles,id',
            'trip-driver' => 'required|exists:users,id',
            'trip-departure-time' => 'required|date_format:Y-m-d\TH:i',
            'trip-arrival-time' => 'required|date_format:Y-m-d\TH:i|after:trip-departure-time',
            'trip-departure-location' => 'required|max:255',
            'trip-arrival-location' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $isDriverAvailable = User::where('id', $request->input('trip-driver'))->where('is_available', 1)->exists();

        if (!$isDriverAvailable) {
            return response()->json(['errors' => 'Driver is not available'], 422);
        }

        $isVehicleAvailable = Vehicle::where('id', $request->input('trip-vehicle'))->whereHas('vehicleStatus', function ($query) {
            $query->where('name', 'available');
        })->exists();

        if (!$isVehicleAvailable) {
            return response()->json(['errors' => 'Vehicle is not available'], 422);
        }

        $data = [
            'vehicle_id' => $request->input('trip-vehicle'),
            'user_id' => $request->input('trip-driver'),
            'departure_time' => $request->input('trip-departure-time'),
            'arrival_time' => $request->input('trip-arrival-time'),
            'departure_location' => $request->input('trip-departure-location'),
            'arrival_location' => $request->input('trip-arrival-location'),
            'trip_status_id' => 1,
        ];

        DB::beginTransaction();
        try {
            Trip::createRecord($data);
            User::where('id', $request->input('trip-driver'))->update(['is_available' => 0]);
            Vehicle::where('id', $request->input('trip-vehicle'))->update(['vehicle_status_id' => 2]);
            DB::commit();
            return response()->json(['message' => 'Trip created successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => "Something went wrong"], 422);
        }
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('moduleAction', ['Trip', 'Update'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'trip-vehicle' => 'required|exists:vehicles,id',
            'trip-driver' => 'required|exists:users,id',
            'trip-departure-time' => 'required|date_format:Y-m-d\TH:i',
            'trip-arrival-time' => 'required|date_format:Y-m-d\TH:i|after:trip-departure-time',
            'trip-departure-location' => 'required|max:255',
            'trip-arrival-location' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $isDriverChanged = Trip::where('id', $id)->where('user_id', '!=', $request->input('trip-driver'))->exists();
        $isVehicleChanged = Trip::where('id', $id)->where('vehicle_id', '!=', $request->input('trip-vehicle'))->exists();

        DB::beginTransaction();
        try {
            if ($isDriverChanged) {
                $isDriverAvailable = User::where('id', $request->input('trip-driver'))->where('is_available', 1)->exists();

                if (!$isDriverAvailable) {
                    return response()->json(['errors' => 'Driver is not available'], 422);
                }

                User::where('id', $request->input('trip-driver'))->update(['is_available' => 0]);
                User::where('id', Trip::find($id)->user_id)->update(['is_available' => 1]);
            }

            if ($isVehicleChanged) {
                $isVehicleAvailable = Vehicle::where('id', $request->input('trip-vehicle'))->whereHas('vehicleStatus', function ($query) {
                    $query->where('name', 'available');
                })->exists();

                if (!$isVehicleAvailable) {
                    return response()->json(['errors' => 'Vehicle is not available'], 422);
                }

                Vehicle::where('id', $request->input('trip-vehicle'))->update(['vehicle_status_id' => 2]);
                Vehicle::where('id', Trip::find($id)->vehicle_id)->update(['vehicle_status_id' => 1]);
            }

            $data = [
                'vehicle_id' => $request->input('trip-vehicle'),
                'user_id' => $request->input('trip-driver'),
                'departure_time' => $request->input('trip-departure-time'),
                'arrival_time' => $request->input('trip-arrival-time'),
                'departure_location' => $request->input('trip-departure-location'),
                'arrival_location' => $request->input('trip-arrival-location'),
            ];

            Trip::updateRecord($data, $id);
            DB::commit();
            return response()->json(['message' => 'Trip updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => "Something went wrong"], 422);
        }
    }

    public function destroy($id)
    {
        if (!Gate::allows('moduleAction', ['Trip', 'Delete'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        DB::beginTransaction();
        try {
            User::where('id', Trip::find($id)->user_id)->update(['is_available' => 1]);
            Vehicle::where('id', Trip::find($id)->vehicle_id)->update(['vehicle_status_id' => 1]);
            Trip::where('id', $id)->delete();
            DB::commit();
            return response()->json(['message' => 'Trip deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => "Something went wrong"], 422);
        }
    }
}
