<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class SparepartController extends Controller
{
    //
    public function index()
    {
        if (!Gate::allows('moduleAction', ['Sparepart', 'Read'])) {
            abort(403);
        }
        return view('spareparts.index');
    }

    public function getSupplierData()
    {
        if (!Gate::allows('moduleAction', ['Supplier', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $suppliers = Supplier::all();
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Sparepart', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
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
        if (!Gate::allows('moduleAction', ['Sparepart', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $search = $request->input('search');
        $spareparts = Sparepart::with('supplier')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->select('*')
            ->selectRaw('CASE WHEN stock <= reorder_level THEN 1 ELSE 0 END AS low_stock')
            ->orderByDesc('low_stock')
            ->paginate(10);

        $canUpdate = Gate::allows('moduleAction', ['Sparepart', 'Update']);
        $canDelete = Gate::allows('moduleAction', ['Sparepart', 'Delete']);
        return response()->json(compact('spareparts', 'canUpdate', 'canDelete'));
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('moduleAction', ['Sparepart', 'Update'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
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
        if (!Gate::allows('moduleAction', ['Sparepart', 'Delete'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
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
