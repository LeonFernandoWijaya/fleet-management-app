<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VehicleDocumentController extends Controller
{
    //
    public function index()
    {
        if (!Gate::allows('moduleAction', ['Document', 'Read'])) {
            abort(403);
        }
        return view('documents.index');
    }

    public function getVehicleDataForDocument(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Document', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $search = $request->search;
        $currentDate = Carbon::now();

        $vehicles = Vehicle::when($search, function ($query) use ($search) {
            $query->where('plate_number', 'like', "%$search%");
        })
            ->withCount(['vehicleDocuments as total_documents'])
            ->withCount(['vehicleDocuments as total_expiry' => function ($query) use ($currentDate) {
                $query->where('expiry_date', '<', $currentDate);
            }])
            ->orderByDesc('total_expiry')
            ->paginate(10);


        return response()->json($vehicles);
    }

    public function getDocumentsData(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Document', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $vehicleId = $request->vehicle_id;
        $currentDate = Carbon::now();

        $documents = VehicleDocument::where('vehicle_id', $vehicleId)
            ->select('*')
            ->selectRaw('CASE WHEN expiry_date < ? THEN 1 ELSE 0 END AS is_expired', [$currentDate])
            ->orderByDesc('is_expired')
            ->paginate(5);

        $canUpdate = Gate::allows('moduleAction', ['Document', 'Update']);
        $canDelete = Gate::allows('moduleAction', ['Document', 'Delete']);
        return response()->json(compact('documents', 'canUpdate', 'canDelete'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Document', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'document-name' => 'required',
            'document-expiry-date' => 'required|date',
            'document-file' => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $originalFileName = $request->file('document-file')->getClientOriginalName();
        $timestamp = Carbon::now()->format('Ymd_His');
        $documentFileName = $timestamp . '_' . $originalFileName;

        $data = [
            'vehicle_id' => $request->vehicle_id,
            'name' => $request['document-name'],
            'expiry_date' => new Carbon($request['document-expiry-date']),
            'path' => $documentFileName,
        ];

        try {
            VehicleDocument::create($data);
            $request->file('document-file')->storeAs('documents', $documentFileName);
            return response()->json(['message' => 'Document uploaded successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Failed to upload document'], 422);
        }
    }

    public function download($filename)
    {
        if (!Gate::allows('moduleAction', ['Document', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $filePath  = 'documents/' . $filename;

        if (Storage::disk('local')->exists($filePath)) {
            return Storage::disk('local')->download($filePath);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('moduleAction', ['Document', 'Update'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'document-name' => 'required',
            'document-expiry-date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        if ($request->file != "undefined") {
            $validator = Validator::make($request->all(), [
                'document-file' => 'required|file|mimes:pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->first()], 422);
            }
            $originalFileName = $request->file('document-file')->getClientOriginalName();
            $timestamp = Carbon::now()->format('Ymd_His');
            $documentFileName = $timestamp . '_' . $originalFileName;

            $data = [
                'name' => $request['document-name'],
                'expiry_date' => new Carbon($request['document-expiry-date']),
                'path' => $documentFileName,
            ];

            $oldDocument = VehicleDocument::find($id);

            try {
                VehicleDocument::updateRecord($id, $data);
                $request->file('document-file')->storeAs('documents', $documentFileName);
                if ($oldDocument->path != null) {
                    Storage::disk('local')->delete('documents/' . $oldDocument->path);
                }
                return response()->json(['message' => 'Document updated successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['errors' => 'Failed to update document'], 422);
            }
        } else {
            $data = [
                'name' => $request['document-name'],
                'expiry_date' => new Carbon($request['document-expiry-date']),
            ];

            try {
                VehicleDocument::updateRecord($id, $data);
                return response()->json(['message' => 'Document updated successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['errors' => 'Failed to update document'], 422);
            }
        }
    }

    public function destroy($id)
    {
        if (!Gate::allows('moduleAction', ['Document', 'Delete'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $document = VehicleDocument::find($id);

        if ($document->path != null) {
            Storage::disk('local')->delete('documents/' . $document->path);
        }

        try {
            VehicleDocument::destroy($id);
            return response()->json(['message' => 'Document deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Failed to delete document'], 422);
        }
    }
}
