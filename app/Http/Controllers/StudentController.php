<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\DataService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct()
    {
        DataService::initializeData();
    }

    /**
     * Display a listing of students (API).
     */
    public function index()
    {
        $students = DataService::getAll('students');
        return response()->json($students);
    }

    /**
     * Display a listing of students (WEB).
     */
    public function webIndex()
    {
        $students = DataService::getAll('students');
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created student (WEB).
     */
    public function webStore(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'status' => 'required|in:ativo,inativo,graduado,suspenso',
            'gpa' => 'nullable|numeric|min:0|max:10',
        ]);

        DataService::add('students', $validated);
        
        return redirect()->route('students.index')
            ->with('success', 'Estudante cadastrado com sucesso!');
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit($id)
    {
        $student = DataService::find('students', $id);
        
        if (!$student) {
            return redirect()->route('students.index')
                ->with('error', 'Estudante não encontrado!');
        }

        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified student (WEB).
     */
    public function webUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'status' => 'required|in:ativo,inativo,graduado,suspenso',
            'gpa' => 'nullable|numeric|min:0|max:10',
        ]);

        $student = DataService::update('students', $id, $validated);
        
        if (!$student) {
            return redirect()->route('students.index')
                ->with('error', 'Estudante não encontrado!');
        }
        
        return redirect()->route('students.index')
            ->with('success', 'Estudante atualizado com sucesso!');
    }

    /**
     * Remove the specified student (WEB).
     */
    public function webDestroy($id)
    {
        $deleted = DataService::delete('students', $id);
        
        if ($deleted) {
            return redirect()->route('students.index')
                ->with('success', 'Estudante removido com sucesso!');
        }
        
        return redirect()->route('students.index')
            ->with('error', 'Estudante não encontrado!');
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
