<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Database\QueryException;

class ProgramController extends Controller
{

    public function GetProgram()
    {
        $programs = Program::all();
        return response()->json($programs);
    }

   

    public function AddProgram(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:programs,name',
        ]);

        $program = new Program();
        $program->name = $request->name;
        $program->save();

        return response()->json(['message' => 'Program added successfully', 'program' => $program], 201);
    }


    public function EditProgram(Request $request, $program_id)
    {
        $program = Program::findOrFail($program_id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
        ]);


        $program->name = $request->name ?? $program->name;
        $program->save();

        return response()->json(['message' => 'Program updated successfully', 'program' => $program], 200);
    }

    // public function DeleteProgram($program_id)
    // {
    //     $program = Program::find($program_id);
    //     if (!$program) {
    //         return response()->json(['message' => 'Program not found'], 404);
    //     }
    //     $program->delete();

    //     return response()->json(['message' => 'Program deleted successfully'], 200);
    // }

    /**
     * Remove the specified program from storage.
     *
     * @param  int  $program_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteProgram($program_id): JsonResponse
    {
        try {
            // Attempt to find the program by ID
            $program = Program::findOrFail($program_id);
            
            // Delete the program
            $program->delete();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Program deleted successfully.'
            ], 200);
        } catch (QueryException $e) {
            // Handle integrity constraint violation
            if ($e->getCode() == '23000') { // SQLSTATE[23000]: Integrity constraint violation
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete program because it is associated with other records.'
                ], 400);
            }

            // Handle other query exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while trying to delete the program.'
            ], 500);
        }
    }
}
