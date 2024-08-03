<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administration;
use Illuminate\Support\Facades\Hash;

class AdministrationController extends Controller
{
    public function GetAllAdministrations()
    {
        $administrations = Administration::with('role')->get();
        return response()->json($administrations, 200);
    }

    public function GetAdministration($administration_id)
    {
        $administration = Administration::with('role')->findOrFail($administration_id);
        return response()->json($administration, 200);
    }

    public function AddAdministration(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'email' => 'required|string|email|max:255|unique:administrations,email',
            'phone' => 'required|string|max:255|unique:administrations,phone',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,role_id',
        ]);

        $administration = Administration::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);

        return response()->json(['message' => 'Administration added successfully', 'administration' => $administration], 201);
    }

    public function EditAdministration(Request $request, $administration_id)
    {
        $administration = Administration::findOrFail($administration_id);

        $request->validate([
            'fname' => 'sometimes|required|string|max:255',
            'lname' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|in:Male,Female,Other',
            'email' => 'sometimes|required|string|email|max:255|unique:administrations,email,' . $administration_id . ',administration_id',
            'phone' => 'sometimes|required|string|max:255|unique:administrations,phone,' . $administration_id . ',administration_id',
            'address' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string|min:8',
            'role_id' => 'sometimes|required|exists:roles,role_id',
        ]);

        $administration->update([
            'fname' => $request->fname ?? $administration->fname,
            'lname' => $request->lname ?? $administration->lname,
            'gender' => $request->gender ?? $administration->gender,
            'email' => $request->email ?? $administration->email,
            'phone' => $request->phone ?? $administration->phone,
            'address' => $request->address ?? $administration->address,
            'role_id' => $request->role_id ?? $administration->role_id,
        ]);

        return response()->json(['message' => 'Administration updated successfully', 'administration' => $administration], 200);
    }

    public function DeleteAdministration($administration_id)
    {
        $administration = Administration::findOrFail($administration_id);

        $administration->delete();

        return response()->json(['message' => 'Administration deleted successfully'], 200);
    }
}
