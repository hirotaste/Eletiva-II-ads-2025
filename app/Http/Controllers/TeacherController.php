<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     */
    public function index()
    {
        $teachers = Teacher::where('is_active', true)->get();
        return response()->json($teachers);
    }

    /**
     * Store a newly created teacher.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers',
            'phone' => 'nullable|string',
            'specialization' => 'required|string',
            'employment_type' => 'required|in:full_time,part_time,contractor',
            'availability' => 'nullable|array',
            'preferences' => 'nullable|array',
        ]);

        $teacher = Teacher::create($validated);
        return response()->json($teacher, 201);
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher)
    {
        return response()->json($teacher->load('classes'));
    }

    /**
     * Update the specified teacher.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:teachers,email,' . $teacher->id,
            'phone' => 'nullable|string',
            'specialization' => 'sometimes|string',
            'employment_type' => 'sometimes|in:full_time,part_time,contractor',
            'availability' => 'nullable|array',
            'preferences' => 'nullable|array',
            'is_active' => 'sometimes|boolean',
        ]);

        $teacher->update($validated);
        return response()->json($teacher);
    }

    /**
     * Remove the specified teacher.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->update(['is_active' => false]);
        return response()->json(['message' => 'Teacher deactivated successfully']);
    }
}
