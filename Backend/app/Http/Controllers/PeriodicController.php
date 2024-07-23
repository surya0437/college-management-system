<?php

namespace App\Http\Controllers;

use App\Models\Periodic;
use Illuminate\Http\Request;

class PeriodicController extends Controller
{
    public function GetPeriodic()
    {
        $periodic = Periodic::all();
        return response()->json($periodic);
    }

    public function AddPeriodic(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:periodics',
        ]);


        $periodic = Periodic::create([
            'name' => $request->name,
            'status' => true,
        ]);

        return response()->json(['message' => 'Periodic added successfully', 'periodic' => $periodic], 201);
    }

    public function EditPeriodic(Request $request, $periodic_id)
    {
        $periodic = Periodic::findOrFail($periodic_id);

        $request->validate([
            'name' => 'required|string|max:255|unique:periodic',
        ]);


        $periodic->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Periodic updated successfully', 'periodic' => $periodic], 200);
    }

    public function DeletePeriodic($periodic_id)
    {
        $periodic = Periodic::find($periodic_id);
        if (!$periodic) {
            return response()->json(['message' => 'Periodic not found'], 404);
        }
        $periodic->delete();

        return response()->json(['message' => 'Periodic deleted successfully'], 200);
    }
}
