<?php

namespace App\Services;

use App\Models\ProfessorDisciplina;
use App\Models\Professor;
use App\Models\Disciplina;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Service para gerenciar preferências de disciplinas dos professores
 * 
 * Responsabilidade Única: Gerenciar a lógica de negócio relacionada 
 * às preferências de disciplinas dos professores
 * 
 * @package App\Services
 */
class PreferenciaService
{
    /**
     * Obtém todas as preferências de um professor
     *
     * @param int $professorId
     * @return Collection
     */
    public function getPreferenciasPorProfessor(int $professorId): Collection
    {
        return ProfessorDisciplina::with(['disciplina'])
            ->byProfessor($professorId)
            ->orderByPreferencia()
            ->get();
    }

    /**
     * Obtém disciplinas disponíveis (que o professor ainda não tem preferência)
     *
     * @param int $professorId
     * @return Collection
     */
    public function getDisciplinasDisponiveis(int $professorId): Collection
    {
        $disciplinasVinculadas = ProfessorDisciplina::byProfessor($professorId)
            ->pluck('disciplina_id')
            ->toArray();

        return Disciplina::whereNotIn('id', $disciplinasVinculadas)
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();
    }

    /**
     * Adiciona uma preferência de disciplina para um professor
     *
     * @param int $professorId
     * @param int $disciplinaId
     * @param int $preferencia
     * @return ProfessorDisciplina
     * @throws Exception
     */
    public function adicionarPreferencia(int $professorId, int $disciplinaId, int $preferencia): ProfessorDisciplina
    {
        // Validar professor
        if (!Professor::find($professorId)) {
            throw new Exception('Professor não encontrado.');
        }

        // Validar disciplina
        if (!Disciplina::find($disciplinaId)) {
            throw new Exception('Disciplina não encontrada.');
        }

        // Validar preferência
        if (!ProfessorDisciplina::isPreferenciaValida($preferencia)) {
            throw new Exception('Preferência deve estar entre 1 e 5.');
        }

        // Verificar se já existe
        $existe = ProfessorDisciplina::where('professor_id', $professorId)
            ->where('disciplina_id', $disciplinaId)
            ->first();

        if ($existe) {
            throw new Exception('Professor já possui preferência cadastrada para esta disciplina.');
        }

        return ProfessorDisciplina::create([
            'professor_id' => $professorId,
            'disciplina_id' => $disciplinaId,
            'preferencia' => $preferencia,
        ]);
    }

    /**
     * Atualiza a preferência de uma disciplina
     *
     * @param int $id
     * @param int $preferencia
     * @return ProfessorDisciplina
     * @throws Exception
     */
    public function atualizarPreferencia(int $id, int $preferencia): ProfessorDisciplina
    {
        $professorDisciplina = ProfessorDisciplina::find($id);

        if (!$professorDisciplina) {
            throw new Exception('Preferência não encontrada.');
        }

        if (!ProfessorDisciplina::isPreferenciaValida($preferencia)) {
            throw new Exception('Preferência deve estar entre 1 e 5.');
        }

        $professorDisciplina->update(['preferencia' => $preferencia]);

        return $professorDisciplina->fresh();
    }

    /**
     * Remove uma preferência de disciplina
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function removerPreferencia(int $id): bool
    {
        $professorDisciplina = ProfessorDisciplina::find($id);

        if (!$professorDisciplina) {
            throw new Exception('Preferência não encontrada.');
        }

        return $professorDisciplina->delete();
    }

    /**
     * Sincroniza múltiplas preferências de uma vez
     * Remove as antigas e cria as novas
     *
     * @param int $professorId
     * @param array $disciplinas Array de ['disciplina_id' => preferencia]
     * @return Collection
     * @throws Exception
     */
    public function sincronizarPreferencias(int $professorId, array $disciplinas): Collection
    {
        DB::beginTransaction();

        try {
            // Remove todas as preferências antigas
            ProfessorDisciplina::byProfessor($professorId)->delete();

            // Adiciona as novas
            $preferencias = collect();
            foreach ($disciplinas as $disciplinaId => $preferencia) {
                if ($preferencia > 0) { // Ignora preferências zeradas
                    $preferencias->push(
                        $this->adicionarPreferencia($professorId, $disciplinaId, $preferencia)
                    );
                }
            }

            DB::commit();
            return $preferencias;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtém estatísticas de preferências de um professor
     *
     * @param int $professorId
     * @return array
     */
    public function getEstatisticas(int $professorId): array
    {
        $preferencias = $this->getPreferenciasPorProfessor($professorId);

        return [
            'total' => $preferencias->count(),
            'muito_alta' => $preferencias->where('preferencia', 5)->count(),
            'alta' => $preferencias->where('preferencia', 4)->count(),
            'media' => $preferencias->where('preferencia', 3)->count(),
            'baixa' => $preferencias->where('preferencia', 2)->count(),
            'muito_baixa' => $preferencias->where('preferencia', 1)->count(),
            'media_preferencia' => $preferencias->avg('preferencia') ?? 0,
        ];
    }

    /**
     * Verifica se um professor pode lecionar uma disciplina
     *
     * @param int $professorId
     * @param int $disciplinaId
     * @return bool
     */
    public function podeLecionar(int $professorId, int $disciplinaId): bool
    {
        return ProfessorDisciplina::where('professor_id', $professorId)
            ->where('disciplina_id', $disciplinaId)
            ->exists();
    }

    /**
     * Obtém a preferência de um professor por uma disciplina específica
     *
     * @param int $professorId
     * @param int $disciplinaId
     * @return int|null
     */
    public function getPreferencia(int $professorId, int $disciplinaId): ?int
    {
        $preferencia = ProfessorDisciplina::where('professor_id', $professorId)
            ->where('disciplina_id', $disciplinaId)
            ->first();

        return $preferencia?->preferencia;
    }

    /**
     * Obtém todos os professores que podem lecionar uma disciplina
     *
     * @param int $disciplinaId
     * @return Collection
     */
    public function getProfessoresPorDisciplina(int $disciplinaId): Collection
    {
        return ProfessorDisciplina::with(['professor'])
            ->byDisciplina($disciplinaId)
            ->orderByPreferencia()
            ->get()
            ->pluck('professor');
    }
}
