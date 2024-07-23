<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

    public function GetSubject()
    {
        $subjects = Subject::with(['program', 'periodic'])->get();
        return response()->json($subjects);
    }

    public function AddSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,program_id',
            'periodic_id' => 'required|exists:periodics,periodic_id',
        ]);


        $subject = Subject::create([
            'name' => $request->name,
            'program_id' => $request->program_id,
            'periodic_id' => $request->periodic_id,
        ]);

        return response()->json(['message' => 'Subject added successfully', 'subject' => $subject], 201);
    }

    public function EditSubject(Request $request, $subject_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,program_id',
            'periodic_id' => 'required|exists:periodics,periodic_id',
        ]);

        $subject = Subject::find($subject_id);
        
        $subject->update([
            'name' => $request->name,
            'program_id' => $request->program_id,
            'periodic_id' => $request->periodic_id,
        ]);

        return response()->json(['message' => 'Subject updated successfully', 'subject' => $subject], 200);
    }

    public function DeleteSubject($subject_id)
    {
        $subject = Subject::find($subject_id);

        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        $subject->delete();

        return response()->json(['message' => 'Subject deleted successfully'], 200);
    }
}
