<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

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

        $status = $request->status == "true" ? true : false;

        $program = new Program();
        $program->name = $request->name;
        $program->status = $status;
        $program->save();

        return response()->json(['message' => 'Program added successfully', 'program' => $program], 201);
    }


    public function EditProgram(Request $request, $program_id)
    {
        $program = Program::findOrFail($program_id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $status = $request->status == "true" ? true : false;

        $program->name = $request->name;
        $program->status = $status;
        $program->save();

        return response()->json(['message' => 'Program updated successfully', 'program' => $program], 200);
    }

    public function DeleteProgram($program_id)
    {
        $program = Program::find($program_id);
        if (!$program) {
            return response()->json(['message' => 'Program not found'], 404);
        }
        $program->delete();

        return response()->json(['message' => 'Program deleted successfully'], 200);
    }
}
