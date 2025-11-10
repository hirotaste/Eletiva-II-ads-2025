<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use App\Models\ProfessorDisponibilidade;
use App\Models\ConfiguracaoHorario;
use App\Services\DisponibilidadeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Controller para gerenciar disponibilidade de professores
 * 
 * Segue os princípios SOLID e Clean Code
 */
class ProfessorDisponibilidadeController extends Controller
{
    /**
     * Service de disponibilidade (Dependency Injection)
     */
    private DisponibilidadeService $disponibilidadeService;

    public function __construct(DisponibilidadeService $disponibilidadeService)
    {
        $this->disponibilidadeService = $disponibilidadeService;
    }

    /**
     * Lista todas as disponibilidades
     * Admin: vê todos os professores
     * Professor: vê apenas a própria disponibilidade
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $professorSelecionado = $request->get('professor_id');
        $professores = collect();
        $grade = null;
        $professor = null;

        if ($user->isAdmin()) {
            // Admin vê todos os professores
            $professores = $this->disponibilidadeService->getProfessoresComDisponibilidade();
            
            if ($professorSelecionado) {
                $professor = Professor::findOrFail($professorSelecionado);
                $grade = $this->disponibilidadeService->getGradeProfessor($professorSelecionado);
            }
        } elseif ($user->isProfessor()) {
            // Professor vê apenas a própria disponibilidade
            $professor = Professor::where('email', $user->email)->first();
            
            if ($professor) {
                $grade = $this->disponibilidadeService->getGradeProfessor($professor->id);
                $professorSelecionado = $professor->id;
            }
        }

        $diasSemana = ProfessorDisponibilidade::diasSemana();

        return view('professor-disponibilidade.index', compact(
            'professores',
            'grade',
            'professor',
            'professorSelecionado',
            'diasSemana'
        ));
    }

    /**
     * Exibe o formulário para editar a grade de disponibilidade
     */
    public function edit(int $id)
    {
        $professor = Professor::findOrFail($id);
        
        // Verifica autorização
        if (!auth()->user()->isAdmin()) {
            $professorLogado = Professor::where('email', auth()->user()->email)->first();
            if (!$professorLogado || $professorLogado->id !== $professor->id) {
                abort(403, 'Você não tem permissão para editar esta disponibilidade.');
            }
        }

        $grade = $this->disponibilidadeService->getGradeProfessor($id);
        $diasSemana = ProfessorDisponibilidade::diasSemana();

        return view('professor-disponibilidade.edit', compact('professor', 'grade', 'diasSemana'));
    }

    /**
     * Salva a grade de disponibilidade
     */
    public function update(Request $request, int $id)
    {
        $professor = Professor::findOrFail($id);
        
        // Verifica autorização
        if (!auth()->user()->isAdmin()) {
            $professorLogado = Professor::where('email', auth()->user()->email)->first();
            if (!$professorLogado || $professorLogado->id !== $professor->id) {
                abort(403, 'Você não tem permissão para editar esta disponibilidade.');
            }
        }

        $validated = $request->validate([
            'grade' => 'required|array',
            'grade.*' => 'array',
        ]);

        $sucesso = $this->disponibilidadeService->salvarGrade($id, $validated['grade']);

        if ($sucesso) {
            return redirect()
                ->route('disponibilidade.index', ['professor_id' => $id])
                ->with('success', 'Disponibilidade salva com sucesso!');
        }

        return back()
            ->with('error', 'Erro ao salvar disponibilidade. Tente novamente.');
    }

    /**
     * Exibe formulário de configuração geral de horários
     * Apenas admin
     */
    public function configuracao()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem acessar esta página.');
        }

        $configuracoes = ConfiguracaoHorario::getGradeCompleta();
        $diasSemana = ProfessorDisponibilidade::diasSemana();

        return view('professor-disponibilidade.configuracao', compact('configuracoes', 'diasSemana'));
    }

    /**
     * Salva configuração geral de horários
     */
    public function salvarConfiguracao(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem realizar esta ação.');
        }

        $validated = $request->validate([
            'configuracoes' => 'required|array',
            'configuracoes.*.dia_semana' => 'required|integer|between:1,7',
            'configuracoes.*.ativo' => 'required|boolean',
            'configuracoes.*.horarios' => 'array',
            'configuracoes.*.horarios.*.hora_inicio' => 'required_with:configuracoes.*.horarios|date_format:H:i',
            'configuracoes.*.horarios.*.hora_fim' => 'required_with:configuracoes.*.horarios|date_format:H:i|after:configuracoes.*.horarios.*.hora_inicio',
        ]);

        try {
            \DB::beginTransaction();

            // Remove configurações antigas
            ConfiguracaoHorario::truncate();

            // Insere novas configurações
            foreach ($validated['configuracoes'] as $config) {
                if ($config['ativo'] && isset($config['horarios'])) {
                    foreach ($config['horarios'] as $horario) {
                        ConfiguracaoHorario::create([
                            'dia_semana' => $config['dia_semana'],
                            'ativo' => true,
                            'hora_inicio' => $horario['hora_inicio'] . ':00',
                            'hora_fim' => $horario['hora_fim'] . ':00',
                            'descricao' => $horario['descricao'] ?? null,
                        ]);
                    }
                }
            }

            \DB::commit();

            return redirect()
                ->route('disponibilidade.configuracao')
                ->with('success', 'Configuração de horários salva com sucesso!');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error("Erro ao salvar configuração: {$e->getMessage()}");
            
            return back()
                ->with('error', 'Erro ao salvar configuração. Tente novamente.');
        }
    }

    /**
     * Copia disponibilidade de um professor para outro
     */
    public function copiar(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem realizar esta ação.');
        }

        $validated = $request->validate([
            'professor_origem_id' => 'required|exists:professores,id',
            'professor_destino_id' => 'required|exists:professores,id|different:professor_origem_id',
        ]);

        $sucesso = $this->disponibilidadeService->copiarDisponibilidade(
            $validated['professor_origem_id'],
            $validated['professor_destino_id']
        );

        if ($sucesso) {
            return redirect()
                ->route('disponibilidade.edit', $validated['professor_destino_id'])
                ->with('success', 'Disponibilidade copiada com sucesso!');
        }

        return back()->with('error', 'Erro ao copiar disponibilidade.');
    }
}
