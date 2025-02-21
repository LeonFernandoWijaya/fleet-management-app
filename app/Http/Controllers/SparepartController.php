<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SparepartController extends Controller
{
    //
    public function index()
    {
        return view('spareparts.index');
    }

    public function getSupplierData()
    {
        $suppliers = Supplier::all();
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sparepart-name' => 'required|string',
            'sparepart-stock' => 'required|integer',
            'sparepart-reorder-level' => 'required|integer',
            'sparepart-supplier' => 'required|integer|exists:suppliers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $findSparepart = Sparepart::where('name', $request->input('sparepart-name'))->first();

        if ($findSparepart) {
            return response()->json(['errors' => 'Sparepart already exists!'], 422);
        }

        $data = [
            'supplier_id' => $request->input('sparepart-supplier'),
            'name' => $request->input('sparepart-name'),
            'stock' => $request->input('sparepart-stock'),
            'reorder_level' => $request->input('sparepart-reorder-level'),
        ];

        try {
            Sparepart::createRecord($data);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Something went wrong'], 404);
        }

        return response()->json(['message' => 'Sparepart created successfully!'], 200);
    }

    public function getSparepartsData(Request $request)
    {
        $search = $request->input('search');
        $spareparts = Sparepart::with('supplier')->when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");
        })
            ->paginate(10);
        return response()->json($spareparts);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sparepart-name' => 'required|string',
            'sparepart-stock' => 'required|integer',
            'sparepart-reorder-level' => 'required|integer',
            'sparepart-supplier' => 'required|integer|exists:suppliers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $findSparepart = Sparepart::find($id);

        if (!$findSparepart) {
            return response()->json(['errors' => 'Sparepart not found!'], 404);
        }

        $data = [
            'supplier_id' => $request->input('sparepart-supplier'),
            'name' => $request->input('sparepart-name'),
            'stock' => $request->input('sparepart-stock'),
            'reorder_level' => $request->input('sparepart-reorder-level'),
        ];

        try {
            Sparepart::updateRecord($id, $data);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Something went wrong'], 404);
        }

        return response()->json(['message' => 'Sparepart updated successfully!'], 200);
    }

    public function destroy($id)
    {
        $findSparepart = Sparepart::find($id);

        if (!$findSparepart) {
            return response()->json(['errors' => 'Sparepart not found!'], 404);
        }

        try {
            $findSparepart->delete();
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Something went wrong'], 404);
        }

        return response()->json(['message' => 'Sparepart deleted successfully!'], 200);
    }
}
