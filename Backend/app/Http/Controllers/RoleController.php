<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function GetRole()
    {
        $Roles = Role::all();
        return response()->json($Roles);
    }



    public function AddRole(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:Roles,name',
        ]);

        $Role = new Role();
        $Role->name = $request->name;
        $Role->save();

        return response()->json(['message' => 'Role added successfully', 'Role' => $Role], 201);
    }


    public function UpdateRole(Request $request, $Role_id)
    {
        $Role = Role::findOrFail($Role_id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        $Role->name = $request->name;
        $Role->save();

        return response()->json(['message' => 'Role updated successfully', 'Role' => $Role], 200);
    }

    public function DeleteRole($Role_id)
    {
        $Role = Role::find($Role_id);
        if (!$Role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $Role->delete();

        return response()->json(['message' => 'Role deleted successfully'], 200);
    }
}
