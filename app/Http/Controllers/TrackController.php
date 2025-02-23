<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class TrackController extends Controller
{
    //
    public function index()
    {
        if (!Gate::allows('moduleAction', ['Track', 'Read'])) {
            abort(403);
        }
        return view('track.index');
    }

    public function getTrackForDriver()
    {
        if (!Gate::allows('moduleAction', ['Track', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $authId = auth()->user()->id;
        $findTrip = Trip::with('user', 'vehicle', 'vehicle.vehicleType')->where('user_id', $authId)->latest()->first();

        return response()->json($findTrip);
    }

    public function reportVehicleForDriver(Request $request, $id)
    {
        if (!Gate::allows('moduleAction', ['Track', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
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
        if (!Gate::allows('moduleAction', ['Track', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
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

    public function startTrackingForDriver(Request $request, $id)
    {
        if (!Gate::allows('moduleAction', ['Track', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $findTrip = Trip::where('user_id', auth()->user()->id)->find($id);
        if (!$findTrip || $findTrip->trip_status_id == 4 || $findTrip->trip_status_id == 3 || $findTrip->trip_status_id == 2) {
            return response()->json(['errors' => 'Something went wrong'], 404);
        }

        $data = [
            'trip_status_id' => 2,
            'actual_departure_time' => now(),
            'departure_latitude' => $request->input('latitude'),
            'departure_longitude' => $request->input('longitude'),
            'latest_latitude' => $request->input('latitude'),
            'latest_longitude' => $request->input('longitude'),
        ];

        try {
            Trip::updateRecord($data, $id);
            return response()->json(['message' => 'Trip started successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Failed to start trip'], 500);
        }
    }

    public function finishTrackingForDriver(Request $request, $id)
    {
        if (!Gate::allows('moduleAction', ['Track', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $findTrip = Trip::where('user_id', auth()->user()->id)->find($id);
        if (!$findTrip || $findTrip->trip_status_id == 4 || $findTrip->trip_status_id == 1) {
            return response()->json(['errors' => 'Something went wrong'], 404);
        }

        $data = [
            'trip_status_id' => 4,
            'actual_arrival_time' => now(),
            'arrival_latitude' => $request->input('latitude'),
            'arrival_longitude' => $request->input('longitude'),
            'latest_latitude' => $request->input('latitude'),
            'latest_longitude' => $request->input('longitude'),
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
        if (!Gate::allows('moduleAction', ['Track', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $authId = auth()->user()->id;
        $findTrips = Trip::with('vehicle', 'vehicle.vehicleType', 'tripStatus')->where('user_id', $authId)->orderBy('created_at', 'desc')->paginate(5);

        return response()->json($findTrips);
    }

    public function updateLocation(Request $request, $id)
    {
        if (!Gate::allows('moduleAction', ['Track', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $findTrip = Trip::where('user_id', auth()->user()->id)->find($id);
        if (!$findTrip || $findTrip->trip_status_id == 4 || $findTrip->trip_status_id == 1) {
            return response()->json(['errors' => 'Something went wrong'], 404);
        }

        $data = [
            'latest_latitude' => $request->input('latitude'),
            'latest_longitude' => $request->input('longitude'),
        ];

        try {
            Trip::updateRecord($data, $id);
            return response()->json(['message' => 'Location updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Failed to update location'], 500);
        }
    }
}
