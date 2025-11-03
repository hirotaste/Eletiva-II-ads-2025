<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use App\Models\PeriodoLetivo;
use App\Models\Disciplina;
use App\Models\Professor;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    /**
     * Display a listing of classes (WEB).
     */
    public function webIndex()
    {
        $turmas = Turma::with(['periodoLetivo', 'disciplina', 'professor'])
            ->orderBy('periodo_letivo_id', 'desc')
            ->orderBy('codigo')
            ->get();
        return view('turmas.index', compact('turmas'));
    }

    /**
     * Show the form for creating a new class.
     */
    public function create()
    {
        $periodos = PeriodoLetivo::orderBy('ano', 'desc')->orderBy('semestre', 'desc')->get();
        $disciplinas = Disciplina::where('ativo', true)->orderBy('nome')->get();
        $professores = Professor::where('ativo', true)->orderBy('nome')->get();
        
        return view('turmas.create', compact('periodos', 'disciplinas', 'professores'));
    }

    /**
     * Store a newly created class (WEB).
     */
    public function webStore(Request $request)
    {
        $validated = $request->validate([
            'periodo_letivo_id' => 'required|exists:periodos_letivos,id',
            'disciplina_id' => 'nullable|exists:disciplinas,id',
            'professor_id' => 'nullable|exists:professores,id',
            'codigo' => 'required|string|max:20',
            'vagas_total' => 'required|integer|min:1',
        ]);

        $validated['vagas_ocupadas'] = 0;

        Turma::create($validated);
        
        return redirect()->route('turmas.index')
            ->with('success', 'Turma cadastrada com sucesso!');
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit($id)
    {
        $turma = Turma::findOrFail($id);
        $periodos = PeriodoLetivo::orderBy('ano', 'desc')->orderBy('semestre', 'desc')->get();
        $disciplinas = Disciplina::where('ativo', true)->orderBy('nome')->get();
        $professores = Professor::where('ativo', true)->orderBy('nome')->get();
        
        return view('turmas.edit', compact('turma', 'periodos', 'disciplinas', 'professores'));
    }

    /**
     * Update the specified class (WEB).
     */
    public function webUpdate(Request $request, $id)
    {
        $turma = Turma::findOrFail($id);
        
        $validated = $request->validate([
            'periodo_letivo_id' => 'required|exists:periodos_letivos,id',
            'disciplina_id' => 'nullable|exists:disciplinas,id',
            'professor_id' => 'nullable|exists:professores,id',
            'codigo' => 'required|string|max:20',
            'vagas_total' => 'required|integer|min:' . $turma->vagas_ocupadas,
        ]);

        $turma->update($validated);
        
        return redirect()->route('turmas.index')
            ->with('success', 'Turma atualizada com sucesso!');
    }

    /**
     * Remove the specified class (WEB).
     */
    public function webDestroy($id)
    {
        $turma = Turma::findOrFail($id);
        
        if ($turma->vagas_ocupadas > 0) {
            return redirect()->route('turmas.index')
                ->with('error', 'Não é possível excluir uma turma com alunos matriculados!');
        }
        
        $turma->delete();
        
        return redirect()->route('turmas.index')
            ->with('success', 'Turma removida com sucesso!');
    }
}
