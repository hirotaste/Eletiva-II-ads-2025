<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of classrooms.
     */
    public function index()
    {
        $classrooms = Classroom::where('is_active', true)->get();
        return response()->json($classrooms);
    }

    /**
     * Store a newly created classroom.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:classrooms',
            'building' => 'required|string',
            'floor' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|in:lecture,lab,auditorium,seminar',
            'resources' => 'nullable|array',
            'has_accessibility' => 'boolean',
        ]);

        $classroom = Classroom::create($validated);
        return response()->json($classroom, 201);
    }

    /**
     * Display the specified classroom.
     */
    public function show(Classroom $classroom)
    {
        return response()->json($classroom->load('classes'));
    }

    /**
     * Update the specified classroom.
     */
    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'code' => 'sometimes|string|unique:classrooms,code,' . $classroom->id,
            'building' => 'sometimes|string',
            'floor' => 'sometimes|string',
            'capacity' => 'sometimes|integer|min:1',
            'type' => 'sometimes|in:lecture,lab,auditorium,seminar',
            'resources' => 'nullable|array',
            'has_accessibility' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        $classroom->update($validated);
        return response()->json($classroom);
    }

    /**
     * Remove the specified classroom.
     */
    public function destroy(Classroom $classroom)
    {
        $classroom->update(['is_active' => false]);
        return response()->json(['message' => 'Classroom deactivated successfully']);
    }
}
