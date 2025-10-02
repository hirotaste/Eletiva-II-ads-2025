<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index()
    {
        $students = Student::where('status', 'active')->get();
        return response()->json($students);
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|unique:students',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'phone' => 'nullable|string',
            'birth_date' => 'required|date',
            'enrollment_date' => 'required|date',
        ]);

        $validated['status'] = 'active';
        $student = Student::create($validated);
        return response()->json($student, 201);
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        return response()->json($student->load('enrollments.discipline'));
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'registration_number' => 'sometimes|string|unique:students,registration_number,' . $student->id,
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string',
            'birth_date' => 'sometimes|date',
            'status' => 'sometimes|in:active,inactive,graduated,suspended',
            'history' => 'nullable|array',
            'gpa' => 'sometimes|numeric|min:0|max:10',
        ]);

        $student->update($validated);
        return response()->json($student);
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Student $student)
    {
        $student->update(['status' => 'inactive']);
        return response()->json(['message' => 'Student deactivated successfully']);
    }
}
