<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use Illuminate\Http\Request;

class DisciplineController extends Controller
{
    /**
     * Display a listing of disciplines.
     */
    public function index()
    {
        $disciplines = Discipline::where('is_active', true)->get();
        return response()->json($disciplines);
    }

    /**
     * Store a newly created discipline.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:disciplines',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'workload_hours' => 'required|integer|min:1',
            'weekly_hours' => 'required|integer|min:1',
            'credits' => 'required|integer|min:1',
            'prerequisites' => 'nullable|array',
            'type' => 'required|in:mandatory,elective,optional',
        ]);

        $discipline = Discipline::create($validated);
        return response()->json($discipline, 201);
    }

    /**
     * Display the specified discipline.
     */
    public function show(Discipline $discipline)
    {
        return response()->json($discipline->load('classes', 'curriculumMatrices'));
    }

    /**
     * Update the specified discipline.
     */
    public function update(Request $request, Discipline $discipline)
    {
        $validated = $request->validate([
            'code' => 'sometimes|string|unique:disciplines,code,' . $discipline->id,
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'workload_hours' => 'sometimes|integer|min:1',
            'weekly_hours' => 'sometimes|integer|min:1',
            'credits' => 'sometimes|integer|min:1',
            'prerequisites' => 'nullable|array',
            'type' => 'sometimes|in:mandatory,elective,optional',
            'is_active' => 'sometimes|boolean',
        ]);

        $discipline->update($validated);
        return response()->json($discipline);
    }

    /**
     * Remove the specified discipline.
     */
    public function destroy(Discipline $discipline)
    {
        $discipline->update(['is_active' => false]);
        return response()->json(['message' => 'Discipline deactivated successfully']);
    }
}
