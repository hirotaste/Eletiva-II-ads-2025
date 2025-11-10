<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use App\Models\Disciplina;
use App\Models\ProfessorDisciplina;
use App\Services\PreferenciaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

/**
 * Controller para gerenciar preferências de disciplinas dos professores
 * 
 * Responsabilidades:
 * - Coordenar requisições HTTP
 * - Aplicar regras de autorização
 * - Delegar lógica de negócio ao Service
 * 
 * @package App\Http\Controllers
 */
class ProfessorDisciplinaController extends Controller
{
    /**
     * Service de preferências
     *
     * @var PreferenciaService
     */
    protected PreferenciaService $preferenciaService;

    /**
     * Construtor com injeção de dependência
     *
     * @param PreferenciaService $preferenciaService
     */
    public function __construct(PreferenciaService $preferenciaService)
    {
        $this->preferenciaService = $preferenciaService;
    }

    /**
     * Exibe a lista de preferências de disciplinas
     * Admin: Pode selecionar qualquer professor
     * Professor: Vê apenas suas próprias preferências
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $professorSelecionado = null;
        $preferencias = collect();
        $estatisticas = null;
        $disciplinasDisponiveis = collect();

        // Se for professor, pega seu ID automaticamente
        if ($user->isProfessor()) {
            $professor = Professor::where('email', $user->email)->first();
            
            if ($professor) {
                $professorSelecionado = $professor;
                $preferencias = $this->preferenciaService->getPreferenciasPorProfessor($professor->id);
                $estatisticas = $this->preferenciaService->getEstatisticas($professor->id);
                $disciplinasDisponiveis = $this->preferenciaService->getDisciplinasDisponiveis($professor->id);
            }
            
            $professores = collect([$professor]); // Professor só vê a si mesmo
        } else {
            // Admin pode ver todos os professores
            $professores = Professor::where('ativo', true)
                ->orderBy('nome')
                ->get();

            // Se foi selecionado um professor
            if ($request->has('professor_id') && $request->professor_id) {
                $professorSelecionado = Professor::find($request->professor_id);
                
                if ($professorSelecionado) {
                    $preferencias = $this->preferenciaService->getPreferenciasPorProfessor($professorSelecionado->id);
                    $estatisticas = $this->preferenciaService->getEstatisticas($professorSelecionado->id);
                    $disciplinasDisponiveis = $this->preferenciaService->getDisciplinasDisponiveis($professorSelecionado->id);
                }
            }
        }

        return view('professor-disciplina.index', compact(
            'professores',
            'professorSelecionado',
            'preferencias',
            'estatisticas',
            'disciplinasDisponiveis'
        ));
    }

    /**
     * Adiciona uma nova preferência de disciplina
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'professor_id' => 'required|exists:professores,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'preferencia' => 'required|integer|min:1|max:5',
        ]);

        // Verificar permissão
        $user = Auth::user();
        if ($user->isProfessor()) {
            $professor = Professor::where('email', $user->email)->first();
            if (!$professor || $professor->id != $validated['professor_id']) {
                return redirect()->back()
                    ->with('error', 'Você só pode gerenciar suas próprias preferências.');
            }
        }

        try {
            $this->preferenciaService->adicionarPreferencia(
                $validated['professor_id'],
                $validated['disciplina_id'],
                $validated['preferencia']
            );

            return redirect()->route('professor-disciplina.index', ['professor_id' => $validated['professor_id']])
                ->with('success', 'Preferência adicionada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Atualiza uma preferência existente
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'preferencia' => 'required|integer|min:1|max:5',
        ]);

        // Verificar permissão
        $professorDisciplina = ProfessorDisciplina::findOrFail($id);
        $user = Auth::user();
        
        if ($user->isProfessor()) {
            $professor = Professor::where('email', $user->email)->first();
            if (!$professor || $professor->id != $professorDisciplina->professor_id) {
                return redirect()->back()
                    ->with('error', 'Você só pode gerenciar suas próprias preferências.');
            }
        }

        try {
            $this->preferenciaService->atualizarPreferencia($id, $validated['preferencia']);

            return redirect()->back()
                ->with('success', 'Preferência atualizada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove uma preferência
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        // Verificar permissão
        $professorDisciplina = ProfessorDisciplina::findOrFail($id);
        $user = Auth::user();
        
        if ($user->isProfessor()) {
            $professor = Professor::where('email', $user->email)->first();
            if (!$professor || $professor->id != $professorDisciplina->professor_id) {
                return redirect()->back()
                    ->with('error', 'Você só pode gerenciar suas próprias preferências.');
            }
        }

        try {
            $professorId = $professorDisciplina->professor_id;
            $this->preferenciaService->removerPreferencia($id);

            return redirect()->route('professor-disciplina.index', ['professor_id' => $professorId])
                ->with('success', 'Preferência removida com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Sincroniza múltiplas preferências de uma vez (AJAX)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sincronizar(Request $request)
    {
        $validated = $request->validate([
            'professor_id' => 'required|exists:professores,id',
            'disciplinas' => 'required|array',
            'disciplinas.*' => 'integer|min:0|max:5',
        ]);

        // Verificar permissão
        $user = Auth::user();
        if ($user->isProfessor()) {
            $professor = Professor::where('email', $user->email)->first();
            if (!$professor || $professor->id != $validated['professor_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você só pode gerenciar suas próprias preferências.'
                ], 403);
            }
        }

        try {
            $this->preferenciaService->sincronizarPreferencias(
                $validated['professor_id'],
                $validated['disciplinas']
            );

            return response()->json([
                'success' => true,
                'message' => 'Preferências sincronizadas com sucesso!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
