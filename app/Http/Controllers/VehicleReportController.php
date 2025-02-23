<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Pail\ValueObjects\Origin\Console;

class VehicleReportController extends Controller
{
    //
    public function index()
    {
        if (!Gate::allows('moduleAction', ['Vehicle Report', 'Read'])) {
            abort(403);
        }
        return view('vehicle-reports.index');
    }

    public function getVehicleDataForReport(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Vehicle Report', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $search = $request->search;

        $vehicles = Vehicle::with('vehicleType')
            ->when($search, function ($query) use ($search) {
                $query->where('plate_number', 'like', "%$search%");
            })
            ->withCount(['vehicleReports' => function ($query) {
                $query->where('is_fixed', 0);
            }])->orderBy('vehicle_reports_count', 'desc')->paginate(10);
        $canUpdate = Gate::allows('moduleAction', ['Vehicle Report', 'Update']);
        return response()->json(compact('vehicles', 'canUpdate'));
    }

    public function getReportDetailsData(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Vehicle Report', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $vehicleId = $request->vehicle_id;

        $vehicleReports = VehicleReport::with('user')->where('vehicle_id', $vehicleId)->orderBy('created_at', 'desc')->paginate(5);

        return response()->json($vehicleReports);
    }

    public function markAsFixed($id)
    {
        if (!Gate::allows('moduleAction', ['Vehicle Report', 'Update'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        try {
            VehicleReport::where('vehicle_id', $id)->update(['is_fixed' => 1]);
            return response()->json(['message' => 'Report marked as fixed']);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Failed to mark as fixed'], 422);
        }
    }
}
