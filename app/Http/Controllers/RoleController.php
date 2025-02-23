<?php

namespace App\Http\Controllers;

use App\Models\ModuleAction;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    //
    public function index()
    {
        if (!Gate::allows('moduleAction', ['Role', 'Read'])) {
            abort(403);
        }
        $moduleActions = ModuleAction::with('module', 'action')->get();
        $groupedModuleActions = $moduleActions->groupBy('module.id')->map(function ($actions, $moduleId) {
            $moduleName = $actions->first()->module->name;
            return [
                'module_id' => $moduleId,
                'module_name' => $moduleName,
                'actions' => $actions->map(function ($action) {
                    return [
                        'id' => $action->action->id,
                        'name' => $action->action->name,
                        'module_action_id' => $action->id
                    ];
                })
            ];
        });
        return view('roles.index', compact('groupedModuleActions'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('moduleAction', ['Role', 'Create'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'roleName' => 'required|string|max:255|unique:roles,name',
            'moduleActions' => 'array',
            'moduleActions.*' => 'required|integer|exists:module_actions,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $role = new Role();
        $role->name = $request->roleName;
        $role->save();

        $moduleActions = ModuleAction::all();
        $rolePermissions = $moduleActions->map(function ($moduleAction) use ($role) {
            return [
                'role_id' => $role->id,
                'module_action_id' => $moduleAction->id,
                "created_at" => now(),
                "updated_at" => now()
            ];
        })->toArray();
        RolePermission::insert($rolePermissions);

        if ($request->moduleActions) {
            RolePermission::where('role_id', $role->id)
                ->whereIn('module_action_id', $request->moduleActions)
                ->update(['is_active' => true]);
        }

        return response()->json(['message' => 'Role created successfully']);
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('moduleAction', ['Role', 'Update'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'roleName' => 'required|string|max:255|unique:roles,name,' . $id,
            'moduleActions' => 'array',
            'moduleActions.*' => 'required|integer|exists:module_actions,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $role = Role::find($request->id);

        if ($role == null) {
            return response()->json(['errors' => 'Role not found'], 422);
        }

        DB::beginTransaction();
        try {
            $role->name = $request->roleName;
            $role->save();
            RolePermission::where('role_id', $role->id)->update(['is_active' => false]);

            if ($request->moduleActions) {
                RolePermission::where('role_id', $role->id)
                    ->whereIn('module_action_id', $request->moduleActions)
                    ->update(['is_active' => true]);
            }
            DB::commit();
            return response()->json(['message' => 'Role updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => 'Something went wrong'], 422);
        }
    }

    public function destroy($id)
    {
        if (!Gate::allows('moduleAction', ['Role', 'Delete'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $role = Role::find($id);

        if ($role == null) {
            return response()->json(['errors' => 'Role not found'], 422);
        }

        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function getRolesData()
    {
        if (!Gate::allows('moduleAction', ['Role', 'Read'])) {
            return response()->json(['errors' => 'Unauthorized'], 403);
        }
        $role = Role::with('permissions')->paginate(8);
        $canUpdate = Gate::allows('moduleAction', ['Role', 'Update']);
        $canDelete = Gate::allows('moduleAction', ['Role', 'Delete']);
        return response()->json(compact('role', 'canUpdate', 'canDelete'));
    }
}
