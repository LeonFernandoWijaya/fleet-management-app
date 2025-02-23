<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    //
    public function index()
    {
        if (!Gate::allows('moduleAction', ['User', 'Read'])) {
            abort(403);
        }
        $roles = Role::all();
        return view('users.index', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('moduleAction', ['User', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'user-name' => 'required|string|max:255',
            'user-email' => 'required|email|max:255|unique:users,email',
            'user-role' => 'required|exists:roles,id',
            'user-status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $data = [
            'name' => $request->input('user-name'),
            'email' => $request->input('user-email'),
            'role_id' => $request->input('user-role'),
            'password' => bcrypt('password'),
            'is_active' => $request->input('user-status'),
        ];

        try {
            User::createRecord($data);
            return response()->json(['message' => 'User created successfully!']);
        } catch (\Exception $e) {
            return response()->json(['errors' => "Something went wrong"], 422);
        }
    }

    public function getUsersData(Request $request)
    {
        if (!Gate::allows('moduleAction', ['User', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $search = $request->input('search');
        $status = $request->input('status');
        $users = User::with('role')->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->when($status, function ($query) use ($status) {
            if ($status == 'active') {
                return $query->where('is_active', 1);
            } else {
                return $query->where('is_active', 0);
            }
        })->paginate(10);
        $canUpdate = Gate::allows('moduleAction', ['User', 'Update']);
        $canDelete = Gate::allows('moduleAction', ['User', 'Delete']);
        return response()->json(compact('users', 'canUpdate', 'canDelete'));
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('moduleAction', ['User', 'Update'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'user-name' => 'required|string|max:255',
            'user-email' => 'required|email|max:255|unique:users,email,' . $id,
            'user-role' => 'required|exists:roles,id',
            'user-status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $data = [
            'name' => $request->input('user-name'),
            'email' => $request->input('user-email'),
            'role_id' => $request->input('user-role'),
            'is_active' => $request->input('user-status'),
        ];

        try {
            User::updateRecord($id, $data);
            return response()->json(['message' => 'User updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['errors' => "Something went wrong"], 422);
        }
    }

    public function destroy($id)
    {
        if (!Gate::allows('moduleAction', ['User', 'Delete'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        try {
            User::where('id', $id)->delete();
            return response()->json(['message' => 'User deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['errors' => "Something went wrong"], 422);
        }
    }
}
