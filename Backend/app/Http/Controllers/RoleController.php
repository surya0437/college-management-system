<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

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
            'name' => 'sometimes|string|max:255',
        ]);


        $Role->name = $request->name ?? $Role->name;
        $Role->save();

        return response()->json(['message' => 'Role updated successfully', 'Role' => $Role], 200);
    }

    // public function DeleteRole($Role_id)
    // {
    //     $Role = Role::find($Role_id);
    //     if (!$Role) {
    //         return response()->json(['message' => 'Role not found'], 404);
    //     }
    //     $Role->delete();

    //     return response()->json(['message' => 'Role deleted successfully'], 200);
    // }


    /**
     * Remove the specified role from storage.
     *
     * @param  int  $role_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteRole($role_id): JsonResponse
    {
        try {
            $role = Role::findOrFail($role_id);
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.'
            ], 200);
        } catch (QueryException $e) {
            if ($e->getCode() == '23000') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete role because it is associated with other records.'
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while trying to delete the role.'
            ], 500);
        }
    }
}
