<?php

namespace App\Http\Controllers;

use App\Models\Periodic;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

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
            'name' => $request->name
        ]);

        return response()->json(['message' => 'Periodic added successfully', 'periodic' => $periodic], 201);
    }

    public function EditPeriodic(Request $request, $periodic_id)
    {
        $periodic = Periodic::findOrFail($periodic_id);

        $request->validate([
            'name' => 'sometimes|string|max:255|unique:periodics',
        ]);


        $periodic->update([
            'name' => $request->name ?? $periodic->name,
        ]);

        return response()->json(['message' => 'Periodic updated successfully', 'periodic' => $periodic], 200);
    }

    // public function DeletePeriodic($periodic_id)
    // {
    //     $periodic = Periodic::find($periodic_id);
    //     if (!$periodic) {
    //         return response()->json(['message' => 'Periodic not found'], 404);
    //     }
    //     $periodic->delete();

    //     return response()->json(['message' => 'Periodic deleted successfully'], 200);
    // }

    /**
     * Remove the specified periodic record from storage.
     *
     * @param  int  $periodic_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeletePeriodic($periodic_id): JsonResponse
    {
        try {
            // Attempt to find the periodic record by ID
            $periodic = Periodic::findOrFail($periodic_id);
            
            // Delete the periodic record
            $periodic->delete();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Periodic record deleted successfully.'
            ], 200);
        } catch (QueryException $e) {
            // Handle integrity constraint violation
            if ($e->getCode() == '23000') { // SQLSTATE[23000]: Integrity constraint violation
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete periodic record because it is associated with other records.'
                ], 400);
            }

            // Handle other query exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while trying to delete the periodic record.'
            ], 500);
        }
    }
}
