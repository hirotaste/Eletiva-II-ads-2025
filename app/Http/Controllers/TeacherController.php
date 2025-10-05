<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Services\DataService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function __construct()
    {
        DataService::initializeData();
    }

    /**
     * Display a listing of teachers (API).
     */
    public function index()
    {
        $teachers = DataService::getAll('teachers');
        return response()->json($teachers);
    }

    /**
     * Display a listing of teachers (WEB).
     */
    public function webIndex()
    {
        $teachers = DataService::getAll('teachers');
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        return view('teachers.create');
    }

    /**
     * Store a newly created teacher (WEB).
     */
    public function webStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'specialization' => 'required|string',
            'employment_type' => 'required|in:integral,meio periodo,contratado',
        ]);

        DataService::add('teachers', $validated);
        
        return redirect()->route('teachers.index')
            ->with('success', 'Professor cadastrado com sucesso!');
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit($id)
    {
        $teacher = DataService::find('teachers', $id);
        
        if (!$teacher) {
            return redirect()->route('teachers.index')
                ->with('error', 'Professor não encontrado!');
        }

        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher (WEB).
     */
    public function webUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'specialization' => 'required|string',
            'employment_type' => 'required|in:integral,meio periodo,contratado',
        ]);

        $teacher = DataService::update('teachers', $id, $validated);
        
        if (!$teacher) {
            return redirect()->route('teachers.index')
                ->with('error', 'Professor não encontrado!');
        }
        
        return redirect()->route('teachers.index')
            ->with('success', 'Professor atualizado com sucesso!');
    }

    /**
     * Remove the specified teacher (WEB).
     */
    public function webDestroy($id)
    {
        $deleted = DataService::delete('teachers', $id);
        
        if ($deleted) {
            return redirect()->route('teachers.index')
                ->with('success', 'Professor removido com sucesso!');
        }
        
        return redirect()->route('teachers.index')
            ->with('error', 'Professor não encontrado!');
    }
}
