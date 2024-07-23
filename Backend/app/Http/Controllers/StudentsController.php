<?php

namespace App\Http\Controllers;

use App\Models\Students;
use Illuminate\Http\Request;

class StudentsController extends Controller
{

    public function GetStudent()
    {
        $students = Students::all();
        return response()->json($students);
    }

    public function AddStudent(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'email' => 'required|string|email|max:255|unique:students',
            'phone' => 'required|string|max:20|unique:students',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'date_of_birth' => 'required|date',
            'program_id' => 'required|exists:programs,program_id',
            'image' => 'nullable|string',
        ]);

        $filename = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $request->fname . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('StudentImage'), $filename);
        }
        $roll_no = 'T' . rand(100000, 999999);
        while (Students::where('roll_no', $roll_no)->exists()) {
            $roll_no = 'S' . rand(100000, 999999);
        }


        $student = Students::create([
            'roll_no' => $roll_no,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => bcrypt($request->password),  // Hash the password
            'date_of_birth' => $request->date_of_birth,
            'program_id' => $request->program_id,
            'image' => $filename,
            'face' => false,
        ]);

        return response()->json(['message' => 'Student added successfully', 'student' => $student], 201);
    }



    public function EditStudent(Request $request, $student_id)
    {
        $student = Students::findOrFail($student_id);

        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'program_id' => 'required|exists:programs,program_id',
            'image' => 'nullable|string',
        ]);

        $filename = $student->image; // Preserve current image if not updated

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $request->fname . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('image'), $filename);

            // Remove old image if exists
            if ($student->image && file_exists(public_path('StudentImage') . '/' . $student->image)) {
                unlink(public_path('StudentImage') . '/' . $student->image);
            }
        }

        $student->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'program_id' => $request->program_id,
            'image' => $filename,
        ]);

        return response()->json(['message' => 'Student updated successfully', 'student' => $student], 200);
    }

    public function DeleteStudent($student_id)
    {
        $student = Students::find($student_id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        } else {
            if ($student->image && file_exists(public_path('StudentImage') . '/' . $student->image)) {
                unlink(public_path('StudentImage') . '/' . $student->image);
            }
            $student->delete();
        }

        return response()->json(['message' => 'Student deleted successfully'], 200);
    }
}
