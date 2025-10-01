<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CurriculumMatrixController;
use App\Http\Controllers\EnrollmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Teachers Routes
Route::apiResource('teachers', TeacherController::class);

// Disciplines Routes
Route::apiResource('disciplines', DisciplineController::class);

// Classrooms Routes
Route::apiResource('classrooms', ClassroomController::class);

// Students Routes
Route::apiResource('students', StudentController::class);

// Curriculum Matrix Routes
Route::apiResource('curriculum-matrices', CurriculumMatrixController::class);
Route::post('curriculum-matrices/{curriculumMatrix}/disciplines', [CurriculumMatrixController::class, 'attachDiscipline']);
Route::delete('curriculum-matrices/{curriculumMatrix}/disciplines/{disciplineId}', [CurriculumMatrixController::class, 'detachDiscipline']);

// Enrollments Routes
Route::apiResource('enrollments', EnrollmentController::class);

// Additional useful routes

// Get teacher's classes for a specific semester
Route::get('teachers/{teacher}/classes', function ($teacherId) {
    return \App\Models\Teacher::findOrFail($teacherId)
        ->classes()
        ->with(['discipline', 'classroom'])
        ->get();
});

// Get student's current enrollments
Route::get('students/{student}/current-enrollments', function ($studentId) {
    $currentYear = date('Y');
    $currentSemester = (int)ceil(date('m') / 6);
    
    return \App\Models\Student::findOrFail($studentId)
        ->enrollments()
        ->where('year', $currentYear)
        ->where('semester', $currentSemester)
        ->with('discipline')
        ->get();
});

// Get available classrooms by type and capacity
Route::get('classrooms/available', function () {
    $type = request('type');
    $minCapacity = request('min_capacity', 0);
    
    $query = \App\Models\Classroom::where('is_active', true)
        ->where('capacity', '>=', $minCapacity);
    
    if ($type) {
        $query->where('type', $type);
    }
    
    return $query->get();
});

// Get disciplines by type
Route::get('disciplines/by-type/{type}', function ($type) {
    return \App\Models\Discipline::where('type', $type)
        ->where('is_active', true)
        ->get();
});

// Get student's academic performance
Route::get('students/{student}/performance', function ($studentId) {
    $student = \App\Models\Student::with(['enrollments' => function ($query) {
        $query->where('status', 'completed')->with('discipline');
    }])->findOrFail($studentId);
    
    $completedEnrollments = $student->enrollments;
    $totalCredits = 0;
    $weightedGrades = 0;
    
    foreach ($completedEnrollments as $enrollment) {
        $credits = $enrollment->discipline->credits;
        $totalCredits += $credits;
        $weightedGrades += $enrollment->grade * $credits;
    }
    
    $gpa = $totalCredits > 0 ? $weightedGrades / $totalCredits : 0;
    
    return [
        'student' => $student->only(['id', 'registration_number', 'name', 'email']),
        'completed_disciplines' => $completedEnrollments->count(),
        'total_credits' => $totalCredits,
        'gpa' => round($gpa, 2),
        'enrollments' => $completedEnrollments
    ];
});

// Check discipline prerequisites for a student
Route::get('students/{student}/can-enroll/{discipline}', function ($studentId, $disciplineId) {
    $student = \App\Models\Student::findOrFail($studentId);
    $discipline = \App\Models\Discipline::findOrFail($disciplineId);
    
    $prerequisites = $discipline->prerequisites ?? [];
    
    if (empty($prerequisites)) {
        return [
            'can_enroll' => true,
            'message' => 'No prerequisites required'
        ];
    }
    
    $completedDisciplines = $student->enrollments()
        ->where('status', 'completed')
        ->pluck('discipline_id')
        ->toArray();
    
    $missingPrerequisites = array_diff($prerequisites, $completedDisciplines);
    
    if (empty($missingPrerequisites)) {
        return [
            'can_enroll' => true,
            'message' => 'All prerequisites completed'
        ];
    }
    
    $missingDisciplines = \App\Models\Discipline::whereIn('id', $missingPrerequisites)->get();
    
    return [
        'can_enroll' => false,
        'message' => 'Missing prerequisites',
        'missing_prerequisites' => $missingDisciplines
    ];
});
