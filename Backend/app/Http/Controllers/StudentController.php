<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

class StudentController extends Controller
{

    public function GetStudent()
    {
        $students = Student::all();
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
            'classShift_id' => 'required|exists:class_shifts,classShift_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $filename = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $request->fname . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('StudentImage'), $filename);
        }

        $roll_no = 'S' . rand(100000, 999999);
        while (Student::where('roll_no', $roll_no)->exists()) {
            $roll_no = 'S' . rand(100000, 999999);
        }

        $student = Student::create([
            'roll_no' => $roll_no,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => bcrypt($request->password),
            'date_of_birth' => $request->date_of_birth,
            'program_id' => $request->program_id,
            'classShift_id' => $request->classShift_id,
            'image' => $filename,
            'face' => false,
        ]);

        return response()->json(['message' => 'Student added successfully', 'student' => $student], 201);
    }




    public function EditStudent(Request $request, $student_id)
    {
        $student = Student::findOrFail($student_id);

        $request->validate([
            'fname' => 'sometimes|string|max:255',
            'lname' => 'sometimes|string|max:255',
            'gender' => 'sometimes|in:Male,Female,Other',
            'email' => 'sometimes|string|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
            'date_of_birth' => 'sometimes|date',
            'program_id' => 'sometimes|exists:programs,program_id',
            'classShift_id' => 'sometimes|exists:class_shifts,classShift_id',
            'image' => 'nullable|string',
        ]);


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
            'fname' => $request->fname ?? $student->fname,
            'lname' => $request->lname ?? $student->lname,
            'gender' => $request->gender ?? $student->gender,
            'email' => $request->email ?? $student->email,
            'phone' => $request->phone ?? $student->phone,
            'address' => $request->address ?? $student->address,
            'date_of_birth' => $request->date_of_birth ?? $student->date_of_birth,
            'program_id' => $request->program_id ?? $student->program_id,
            'program_id' => $request->classShift_id ?? $student->classShift_id,
            'image' => $filename ?? $student->image,
        ]);

        return response()->json(['message' => 'Student updated successfully', 'student' => $student], 200);
    }

    // public function DeleteStudent($student_id)
    // {
    //     $student = Student::find($student_id);
    //     if (!$student) {
    //         return response()->json(['message' => 'Student not found'], 404);
    //     } else {
    //         if ($student->image && file_exists(public_path('StudentImage') . '/' . $student->image)) {
    //             unlink(public_path('StudentImage') . '/' . $student->image);
    //         }
    //         $student->delete();
    //     }

    //     return response()->json(['message' => 'Student deleted successfully'], 200);
    // }


    /**
     * Remove the specified student from storage.
     *
     * @param  int  $student_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteStudent($student_id): JsonResponse
    {
        try {
            // Attempt to find the student by ID
            $student = Student::findOrFail($student_id);

            // Handle image deletion if it exists
            if ($student->image && file_exists(public_path('StudentImage') . '/' . $student->image)) {
                unlink(public_path('StudentImage') . '/' . $student->image);
            }

            // Delete the student record
            $student->delete();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully.'
            ], 200);
        } catch (QueryException $e) {
            // Handle integrity constraint violation
            if ($e->getCode() == '23000') { // SQLSTATE[23000]: Integrity constraint violation
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete student because they are associated with other records.'
                ], 400);
            }

            // Handle other query exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while trying to delete the student.'
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
