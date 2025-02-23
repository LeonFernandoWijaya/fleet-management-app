<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    //
    public function index()
    {
        if (!Gate::allows('moduleAction', ['Supplier', 'Read'])) {
            abort(403);
        }
        return view('suppliers.index');
    }

    public function getSupplierData(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Supplier', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $search = $request->input('search');
        $suppliers = Supplier::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");
        })
            ->paginate(10);

        $canUpdate = Gate::allows('moduleAction', ['Supplier', 'Update']);
        $canDelete = Gate::allows('moduleAction', ['Supplier', 'Delete']);
        return response()->json(compact('suppliers', 'canUpdate', 'canDelete'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Supplier', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'supplier-name' => 'required|string|max:255',
            'supplier-address' => 'required|string|max:255',
            'supplier-contact-number' => 'required|string|regex:/^[0-9]{10,15}$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $findPhone = Supplier::where('contact_number', $request->input('supplier-contact-number'))->first();

        if ($findPhone) {
            return response()->json(['errors' => 'Phone number already exists'], 422);
        }

        $data = [
            'name' => $request->input('supplier-name'),
            'address' => $request->input('supplier-address'),
            'contact_number' => $request->input('supplier-contact-number'),
        ];

        try {
            Supplier::createRecord($data);
        } catch (\Exception $e) {
            return response()->json(['errors' => "Something went wrong"], 422);
        }

        return response()->json(['message' => 'Supplier created successfully']);
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('moduleAction', ['Supplier', 'Update'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'supplier-name' => 'required|string|max:255',
            'supplier-address' => 'required|string|max:255',
            'supplier-contact-number' => 'required|string|regex:/^[0-9]{10,15}$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $findPhone = Supplier::where('contact_number', $request->input('supplier-contact-number'))->where('id', '!=', $id)->first();

        if ($findPhone) {
            return response()->json(['errors' => 'Phone number already exists'], 422);
        }

        $data = [
            'name' => $request->input('supplier-name'),
            'address' => $request->input('supplier-address'),
            'contact_number' => $request->input('supplier-contact-number'),
        ];

        try {
            Supplier::updateRecord($id, $data);
        } catch (\Exception $e) {
            return response()->json(['errors' => "Something went wrong"], 422);
        }

        return response()->json(['message' => 'Supplier updated successfully']);
    }

    public function destroy($id)
    {
        if (!Gate::allows('moduleAction', ['Supplier', 'Delete'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        try {
            Supplier::where('id', $id)->delete();
        } catch (\Exception $e) {
            return response()->json(['errors' => "Something went wrong"], 422);
        }

        return response()->json(['message' => 'Supplier deleted successfully']);
    }
}
