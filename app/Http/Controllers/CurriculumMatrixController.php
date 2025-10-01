<?php

namespace App\Http\Controllers;

use App\Models\CurriculumMatrix;
use Illuminate\Http\Request;

class CurriculumMatrixController extends Controller
{
    /**
     * Display a listing of curriculum matrices.
     */
    public function index()
    {
        $matrices = CurriculumMatrix::where('is_active', true)->get();
        return response()->json($matrices);
    }

    /**
     * Store a newly created curriculum matrix.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:curriculum_matrix',
            'name' => 'required|string|max:255',
            'year' => 'required|integer',
            'semester' => 'required|integer|min:1|max:2',
            'description' => 'nullable|string',
        ]);

        $matrix = CurriculumMatrix::create($validated);
        return response()->json($matrix, 201);
    }

    /**
     * Display the specified curriculum matrix.
     */
    public function show(CurriculumMatrix $curriculumMatrix)
    {
        return response()->json($curriculumMatrix->load('disciplines'));
    }

    /**
     * Update the specified curriculum matrix.
     */
    public function update(Request $request, CurriculumMatrix $curriculumMatrix)
    {
        $validated = $request->validate([
            'code' => 'sometimes|string|unique:curriculum_matrix,code,' . $curriculumMatrix->id,
            'name' => 'sometimes|string|max:255',
            'year' => 'sometimes|integer',
            'semester' => 'sometimes|integer|min:1|max:2',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $curriculumMatrix->update($validated);
        return response()->json($curriculumMatrix);
    }

    /**
     * Remove the specified curriculum matrix.
     */
    public function destroy(CurriculumMatrix $curriculumMatrix)
    {
        $curriculumMatrix->update(['is_active' => false]);
        return response()->json(['message' => 'Curriculum matrix deactivated successfully']);
    }

    /**
     * Attach a discipline to the curriculum matrix.
     */
    public function attachDiscipline(Request $request, CurriculumMatrix $curriculumMatrix)
    {
        $validated = $request->validate([
            'discipline_id' => 'required|exists:disciplines,id',
            'semester' => 'required|integer|min:1',
            'period' => 'required|integer|min:1|max:3',
        ]);

        $curriculumMatrix->disciplines()->attach($validated['discipline_id'], [
            'semester' => $validated['semester'],
            'period' => $validated['period'],
        ]);

        return response()->json(['message' => 'Discipline attached successfully']);
    }

    /**
     * Detach a discipline from the curriculum matrix.
     */
    public function detachDiscipline(CurriculumMatrix $curriculumMatrix, $disciplineId)
    {
        $curriculumMatrix->disciplines()->detach($disciplineId);
        return response()->json(['message' => 'Discipline detached successfully']);
    }
}
