<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrollments.
     */
    public function index()
    {
        $enrollments = Enrollment::with(['student', 'discipline'])->get();
        return response()->json($enrollments);
    }

    /**
     * Store a newly created enrollment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'discipline_id' => 'required|exists:disciplines,id',
            'year' => 'required|integer',
            'semester' => 'required|integer|min:1|max:2',
        ]);

        $validated['status'] = 'enrolled';
        $enrollment = Enrollment::create($validated);
        return response()->json($enrollment->load(['student', 'discipline']), 201);
    }

    /**
     * Display the specified enrollment.
     */
    public function show(Enrollment $enrollment)
    {
        return response()->json($enrollment->load(['student', 'discipline']));
    }

    /**
     * Update the specified enrollment.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:enrolled,completed,failed,withdrawn',
            'grade' => 'nullable|numeric|min:0|max:10',
            'attendance_percentage' => 'nullable|integer|min:0|max:100',
        ]);

        $enrollment->update($validated);
        return response()->json($enrollment->load(['student', 'discipline']));
    }

    /**
     * Remove the specified enrollment.
     */
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->update(['status' => 'withdrawn']);
        return response()->json(['message' => 'Enrollment withdrawn successfully']);
    }
}
