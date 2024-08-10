<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{

    public function GetTeacher()
    {
        $teachers = Teacher::all();
        return response()->json($teachers);
    }

    public function AddTeacher(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'email' => 'required|string|email|max:255|unique:teachers',
            'phone' => 'required|string|max:20|unique:teachers',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'date_of_birth' => 'required|date',
            'education' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'in_time' => 'required|date_format:H:i',
            'working_hour' => 'required|integer',
            // 'out_time' => 'required|date_format:H:i',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'face' => 'nullable|boolean',
        ]);

        $filename = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $request->fname . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('TeacherImage'), $filename);
        }
        $roll_no = 'T' . rand(100000, 999999);
        while (Teacher::where('roll_no', $roll_no)->exists()) {
            $roll_no = 'T' . rand(100000, 999999);
        }


        $in_time = Carbon::createFromFormat('H:i', $request->in_time);
        $out_time = $in_time->copy()->addHours($request->working_hour);

        $teacher = Teacher::create([
            'roll_no' => $roll_no,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => bcrypt($request->password),
            'date_of_birth' => $request->date_of_birth,
            'education' => $request->education,
            'specialization' => $request->specialization,
            'in_time' => $request->in_time,
            'working_hour' => $request->working_hour,
            'out_time' => $out_time->format('H:i'),
            // 'out_time' => $request->out_time,
            'image' => $filename,
            'face' => false,
        ]);

        return response()->json(['message' => 'Teacher added successfully', 'teacher' => $teacher], 201);
    }

    public function EditTeacher(Request $request, $teacher_id)
    {
        $teacher = Teacher::findOrFail($teacher_id);

        $request->validate([
            'fname' => 'sometimes|string|max:255',
            'lname' => 'sometimes|string|max:255',
            'gender' => 'sometimes|in:Male,Female,Other',
            'email' => 'sometimes|string|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
            'password' => 'sometimes|string|min:8',
            'date_of_birth' => 'sometimes|date',
            'education' => 'sometimes|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'in_time' => 'sometimes|date_format:H:i',
            'working_hour' => 'sometimes|integer',
            'out_time' => 'sometimes|date_format:H:i',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'face' => 'nullable|boolean',
        ]);


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $request->fname . '_' . rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('TeacherImage'), $filename);

            // Remove old image if exists
            if ($teacher->image && file_exists(public_path('TeacherImage') . '/' . $teacher->image)) {
                unlink(public_path('TeacherImage') . '/' . $teacher->image);
            }
        }


        $in_time = Carbon::createFromFormat('H:i', $request->in_time);
        $out_time = $in_time->copy()->addHours($request->working_hour ?? $teacher->working_hour);

        $teacher->update([
            'fname' => $request->fname ?? $request->fname,
            'lname' => $request->lname ?? $request->lname,
            'gender' => $request->gender ?? $request->gender,
            'email' => $request->email ?? $request->email,
            'phone' => $request->phone ?? $request->phone,
            'address' => $request->address ?? $request->address,
            'date_of_birth' => $request->date_of_birth ?? $request->date_of_birth,
            'education' => $request->education ?? $request->education,
            'specialization' => $request->specialization ?? $request->specialization,
            'in_time' => $request->in_time ?? $request->in_time,
            'working_hour' => $request->working_hour ?? $teacher->working_hour,
            'out_time' => $out_time->format('H:i'),
            'image' => $filename ?? $teacher->image,
        ]);

        return response()->json(['message' => 'Teacher updated successfully', 'teacher' => $teacher], 200);
    }

    public function DeleteTeacher($teacher_id)
    {
        $teacher = Teacher::find($teacher_id);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        } else {
            if ($teacher->image && file_exists(public_path('TeacherImage') . '/' . $teacher->image)) {
                unlink(public_path('TeacherImage') . '/' . $teacher->image);
            }
            $teacher->delete();
        }

        return response()->json(['message' => 'Teacher deleted successfully'], 200);
    }
}
