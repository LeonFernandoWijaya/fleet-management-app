<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    //
    public function index()
    {
        return view('suppliers.index');
    }

    public function getSupplierData(Request $request)
    {
        $search = $request->input('search');
        $suppliers = Supplier::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");
        })
            ->paginate(10);
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
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
        try {
            Supplier::where('id', $id)->delete();
        } catch (\Exception $e) {
            return response()->json(['errors' => "Something went wrong"], 422);
        }

        return response()->json(['message' => 'Supplier deleted successfully']);
    }
}
