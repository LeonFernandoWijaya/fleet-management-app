<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TrackController extends Controller
{
    //
    public function index()
    {
        return view('track.index');
    }

    public function getTrackForDriver()
    {
        $authId = auth()->user()->id;
        $findTrip = Trip::with('user', 'vehicle', 'vehicle.vehicleType')->where('user_id', $authId)->latest()->first();

        return response()->json($findTrip);
    }

    public function reportVehicleForDriver(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vehicle-report-description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $findTrip = Trip::where('user_id', auth()->user()->id)->find($id);
        if (!$findTrip || $findTrip->trip_status_id == 4) {
            return response()->json(['errors' => 'Something went wrong'], 404);
        }

        $data = [
            'vehicle_id' => $findTrip->vehicle_id,
            'description' => $request->input('vehicle-report-description'),
            'user_id' => auth()->user()->id,
        ];

        try {
            VehicleReport::createRecord($data);
            return response()->json(['message' => 'Vehicle issue reported successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Failed to report vehicle issue'], 500);
        }
    }

    public function reportTripForDriver(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'trip-report-description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $findTrip = Trip::where('user_id', auth()->user()->id)->find($id);
        if (!$findTrip || $findTrip->trip_status_id == 4 || $findTrip->trip_status_id == 3 || $findTrip->trip_status_id == 1) {
            return response()->json(['errors' => 'Something went wrong'], 404);
        }

        $data = [
            'trip_issue' => $request->input('trip-report-description'),
            'trip_status_id' => 3,
        ];

        try {
            Trip::updateRecord($data, $id);
            return response()->json(['message' => 'Trip issue reported successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Failed to report trip issue'], 500);
        }
    }

    public function startTrackingForDriver($id)
    {
        $findTrip = Trip::where('user_id', auth()->user()->id)->find($id);
        if (!$findTrip || $findTrip->trip_status_id == 4 || $findTrip->trip_status_id == 3 || $findTrip->trip_status_id == 2) {
            return response()->json(['errors' => 'Something went wrong'], 404);
        }

        $data = [
            'trip_status_id' => 2,
            'actual_departure_time' => now(),
        ];

        try {
            Trip::updateRecord($data, $id);
            return response()->json(['message' => 'Trip started successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Failed to start trip'], 500);
        }
    }

    public function finishTrackingForDriver($id)
    {
        $findTrip = Trip::where('user_id', auth()->user()->id)->find($id);
        if (!$findTrip || $findTrip->trip_status_id == 4 || $findTrip->trip_status_id == 1) {
            return response()->json(['errors' => 'Something went wrong'], 404);
        }

        $data = [
            'trip_status_id' => 4,
            'actual_arrival_time' => now(),
        ];

        DB::beginTransaction();
        try {
            Trip::updateRecord($data, $id);
            Vehicle::updateRecord($findTrip->vehicle_id, ['vehicle_status_id' => 1]);
            User::updateRecord(auth()->user()->id, ['is_available' => 1]);
            DB::commit();
            return response()->json(['message' => 'Trip finished successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => 'Failed to finish trip'], 500);
        }
    }

    public function trackHistoryForDriver()
    {
        $authId = auth()->user()->id;
        $findTrips = Trip::with('vehicle', 'vehicle.vehicleType', 'tripStatus')->where('user_id', $authId)->orderBy('created_at', 'desc')->paginate(5);

        return response()->json($findTrips);
    }
}
