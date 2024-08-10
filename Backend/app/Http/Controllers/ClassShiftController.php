<?php

namespace App\Http\Controllers;

use App\Models\ClassShift;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

class ClassShiftController extends Controller
{
    public function GetClassShifts()
    {
        $classShifts = ClassShift::all();
        return response()->json($classShifts, 200);
    }

    public function AddClassShift(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:class_shifts,name',
            'in_time' => 'required|date_format:H:i',
            'out_time' => 'required|date_format:H:i|after:in_time',
        ]);

        $classShift = ClassShift::create([
            'name' => $request->name,
            'in_time' => $request->in_time,
            'out_time' => $request->out_time,
        ]);

        return response()->json(['message' => 'Class shift added successfully', 'classShift' => $classShift], 201);
    }

    public function EditClassShift(Request $request, $classShift_id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:class_shifts,name,' . $classShift_id . ',classShift_id',
            'in_time' => 'sometimes|date_format:H:i',
            'out_time' => 'sometimes|date_format:H:i|after:in_time',
        ]);

        $classShift = ClassShift::findOrFail($classShift_id);
        $classShift->name = $request->name ?? $classShift->name;
        $classShift->in_time = $request->in_time ?? $classShift->in_time;
        $classShift->out_time = $request->out_time ?? $classShift->out_time;
        $classShift->save();

        return response()->json(['message' => 'Class shift updated successfully', 'classShift' => $classShift], 200);
    }

    /**
     * Remove the specified class shift from storage.
     *
     * @param  int  $classShift_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($classShift_id): JsonResponse
    {
        try {
            // Attempt to find the class shift by ID
            $classShift = ClassShift::findOrFail($classShift_id);

            // Delete the class shift
            $classShift->delete();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Class shift deleted successfully.'
            ], 200);
        } catch (QueryException $e) {
            // Handle integrity constraint violation
            if ($e->getCode() == '23000') { // SQLSTATE[23000]: Integrity constraint violation
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete class shift because it is associated with other records.'
                ], 400);
            }

            // Handle other query exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while trying to delete the class shift.'
            ], 500);
        } catch (\Exception $e) {
            // Handle general exceptions
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
