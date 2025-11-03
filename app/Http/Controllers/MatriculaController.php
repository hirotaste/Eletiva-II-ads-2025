<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\Aluno;
use App\Models\Turma;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    /**
     * Display a listing of enrollments (WEB).
     */
    public function webIndex()
    {
        $matriculas = Matricula::with(['aluno', 'turma.disciplina', 'turma.periodoLetivo'])
            ->orderBy('data_matricula', 'desc')
            ->get();
        return view('matriculas.index', compact('matriculas'));
    }

    /**
     * Show the form for creating a new enrollment.
     */
    public function create()
    {
        $alunos = Aluno::where('ativo', true)->orderBy('nome')->get();
        $turmas = Turma::with(['disciplina', 'periodoLetivo', 'professor'])
            ->whereRaw('vagas_ocupadas < vagas_total')
            ->orderBy('periodo_letivo_id', 'desc')
            ->get();
        
        return view('matriculas.create', compact('alunos', 'turmas'));
    }

    /**
     * Store a newly created enrollment (WEB).
     */
    public function webStore(Request $request)
    {
        $validated = $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'turma_id' => 'required|exists:turmas,id',
            'status' => 'nullable|in:matriculado,trancado,cancelado',
            'is_dependencia' => 'boolean',
        ]);

        // Check if student is already enrolled in this class
        $existente = Matricula::where('aluno_id', $validated['aluno_id'])
            ->where('turma_id', $validated['turma_id'])
            ->first();
            
        if ($existente) {
            return redirect()->back()
                ->with('error', 'Aluno já está matriculado nesta turma!')
                ->withInput();
        }

        // Check if class has available spots
        $turma = Turma::findOrFail($validated['turma_id']);
        if (!$turma->temVagasDisponiveis()) {
            return redirect()->back()
                ->with('error', 'Turma não possui vagas disponíveis!')
                ->withInput();
        }

        // Set default status if not provided
        if (!isset($validated['status'])) {
            $validated['status'] = 'matriculado';
        }

        // Set enrollment date to current timestamp
        $validated['data_matricula'] = now();

        // Create enrollment
        $matricula = Matricula::create($validated);

        // Update occupied spots count
        if ($validated['status'] === 'matriculado') {
            $turma->increment('vagas_ocupadas');
        }
        
        return redirect()->route('matriculas.index')
            ->with('success', 'Matrícula realizada com sucesso!');
    }

    /**
     * Show the form for editing the specified enrollment.
     */
    public function edit($id)
    {
        $matricula = Matricula::findOrFail($id);
        $alunos = Aluno::where('ativo', true)->orderBy('nome')->get();
        $turmas = Turma::with(['disciplina', 'periodoLetivo', 'professor'])
            ->orderBy('periodo_letivo_id', 'desc')
            ->get();
        
        return view('matriculas.edit', compact('matricula', 'alunos', 'turmas'));
    }

    /**
     * Update the specified enrollment (WEB).
     */
    public function webUpdate(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);
        $statusAnterior = $matricula->status;
        
        $validated = $request->validate([
            'status' => 'required|in:matriculado,trancado,cancelado',
            'is_dependencia' => 'boolean',
        ]);

        $matricula->update($validated);

        // Update occupied spots count based on status change
        $turma = $matricula->turma;
        if ($statusAnterior === 'matriculado' && $validated['status'] !== 'matriculado') {
            $turma->decrement('vagas_ocupadas');
        } elseif ($statusAnterior !== 'matriculado' && $validated['status'] === 'matriculado') {
            $turma->increment('vagas_ocupadas');
        }
        
        return redirect()->route('matriculas.index')
            ->with('success', 'Matrícula atualizada com sucesso!');
    }

    /**
     * Remove the specified enrollment (WEB).
     */
    public function webDestroy($id)
    {
        $matricula = Matricula::findOrFail($id);
        
        // Update occupied spots count if enrollment was active
        if ($matricula->status === 'matriculado') {
            $matricula->turma->decrement('vagas_ocupadas');
        }
        
        $matricula->delete();
        
        return redirect()->route('matriculas.index')
            ->with('success', 'Matrícula removida com sucesso!');
    }
}
