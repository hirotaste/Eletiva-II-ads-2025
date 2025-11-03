<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Display a listing of courses (WEB).
     */
    public function webIndex()
    {
        $cursos = Curso::orderBy('nome')->get();
        return view('cursos.index', compact('cursos'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('cursos.create');
    }

    /**
     * Store a newly created course (WEB).
     */
    public function webStore(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'codigo' => 'required|string|max:20|unique:cursos,codigo',
            'duracao_semestres' => 'required|integer|min:1',
            'carga_horaria_total' => 'required|integer|min:1',
        ]);

        Curso::create($validated);
        
        return redirect()->route('cursos.index')
            ->with('success', 'Curso cadastrado com sucesso!');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit($id)
    {
        $curso = Curso::findOrFail($id);
        return view('cursos.edit', compact('curso'));
    }

    /**
     * Update the specified course (WEB).
     */
    public function webUpdate(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'codigo' => 'required|string|max:20|unique:cursos,codigo,' . $id,
            'duracao_semestres' => 'required|integer|min:1',
            'carga_horaria_total' => 'required|integer|min:1',
        ]);

        $curso->update($validated);
        
        return redirect()->route('cursos.index')
            ->with('success', 'Curso atualizado com sucesso!');
    }

    /**
     * Remove the specified course (WEB).
     */
    public function webDestroy($id)
    {
        $curso = Curso::findOrFail($id);
        $curso->delete();
        
        return redirect()->route('cursos.index')
            ->with('success', 'Curso removido com sucesso!');
    }
}
