<?php

namespace App\Services;

use App\Models\ProfessorDisponibilidade;
use App\Models\Professor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service para gerenciar a disponibilidade de professores
 * 
 * Implementa a lógica de negócio relacionada à disponibilidade
 * Segue os princípios SOLID (Single Responsibility, Dependency Inversion)
 */
class DisponibilidadeService
{
    /**
     * Obtém a grade de disponibilidade de um professor
     */
    public function getGradeProfessor(int $professorId): array
    {
        return ProfessorDisponibilidade::getGradeProfessor($professorId);
    }

    /**
     * Salva a grade completa de disponibilidade de um professor
     * 
     * @param int $professorId ID do professor
     * @param array $grade Array com a grade de disponibilidade
     * @return bool Sucesso da operação
     */
    public function salvarGrade(int $professorId, array $grade): bool
    {
        try {
            DB::beginTransaction();

            // Remove todas as disponibilidades antigas do professor
            ProfessorDisponibilidade::where('professor_id', $professorId)->delete();

            // Agrupa horários consecutivos do mesmo dia
            $disponibilidades = $this->agruparHorariosConsecutivos($grade);

            // Insere novas disponibilidades
            foreach ($disponibilidades as $disp) {
                ProfessorDisponibilidade::create([
                    'professor_id' => $professorId,
                    'dia_semana' => $disp['dia'],
                    'hora_inicio' => sprintf('%02d:00:00', $disp['hora_inicio']),
                    'hora_fim' => sprintf('%02d:00:00', $disp['hora_fim']),
                    'preferencia' => $disp['preferencia'] ?? 3,
                ]);
            }

            DB::commit();
            
            Log::info("Disponibilidade salva para professor {$professorId}", [
                'total_slots' => count($disponibilidades)
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao salvar disponibilidade: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Agrupa horários consecutivos para otimizar armazenamento
     * 
     * @param array $grade Grade de disponibilidade
     * @return array Disponibilidades agrupadas
     */
    private function agruparHorariosConsecutivos(array $grade): array
    {
        $disponibilidades = [];

        foreach ($grade as $dia => $horarios) {
            $horaInicio = null;
            $horaFim = null;
            $preferencia = null;

            for ($hora = 0; $hora < 24; $hora++) {
                $disponivel = isset($horarios[$hora]) && $horarios[$hora]['disponivel'];
                $pref = $horarios[$hora]['preferencia'] ?? 3;

                if ($disponivel) {
                    if ($horaInicio === null) {
                        // Início de um novo bloco
                        $horaInicio = $hora;
                        $preferencia = $pref;
                    }
                    $horaFim = $hora + 1;
                } else {
                    if ($horaInicio !== null) {
                        // Fim de um bloco
                        $disponibilidades[] = [
                            'dia' => $dia,
                            'hora_inicio' => $horaInicio,
                            'hora_fim' => $horaFim,
                            'preferencia' => $preferencia,
                        ];
                        $horaInicio = null;
                        $horaFim = null;
                    }
                }
            }

            // Se terminou o dia com um bloco aberto
            if ($horaInicio !== null) {
                $disponibilidades[] = [
                    'dia' => $dia,
                    'hora_inicio' => $horaInicio,
                    'hora_fim' => $horaFim,
                    'preferencia' => $preferencia,
                ];
            }
        }

        return $disponibilidades;
    }

    /**
     * Obtém lista de professores com estatísticas de disponibilidade
     */
    public function getProfessoresComDisponibilidade(): Collection
    {
        return Professor::select('professores.*')
            ->selectRaw('COUNT(pd.id) as total_slots')
            ->selectRaw('SUM(HOUR(TIMEDIFF(pd.hora_fim, pd.hora_inicio))) as total_horas')
            ->leftJoin('professor_disponibilidade as pd', 'professores.id', '=', 'pd.professor_id')
            ->groupBy('professores.id')
            ->orderBy('professores.nome')
            ->get();
    }

    /**
     * Valida se o professor tem disponibilidade em determinado horário
     */
    public function temDisponibilidade(int $professorId, int $diaSemana, string $horaInicio, string $horaFim): bool
    {
        return ProfessorDisponibilidade::where('professor_id', $professorId)
            ->where('dia_semana', $diaSemana)
            ->where('hora_inicio', '<=', $horaInicio)
            ->where('hora_fim', '>=', $horaFim)
            ->exists();
    }

    /**
     * Obtém conflitos de disponibilidade entre professores
     */
    public function getConflitos(int $professorId): array
    {
        $disponibilidades = ProfessorDisponibilidade::where('professor_id', $professorId)
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        $conflitos = [];

        foreach ($disponibilidades as $i => $disp1) {
            foreach ($disponibilidades as $j => $disp2) {
                if ($i >= $j) continue;
                
                if ($disp1->dia_semana === $disp2->dia_semana && 
                    $disp1->temConflitoCom($disp2->hora_inicio, $disp2->hora_fim)) {
                    $conflitos[] = [
                        'dia' => $disp1->dia_semana,
                        'horario1' => "{$disp1->hora_inicio} - {$disp1->hora_fim}",
                        'horario2' => "{$disp2->hora_inicio} - {$disp2->hora_fim}",
                    ];
                }
            }
        }

        return $conflitos;
    }

    /**
     * Copia disponibilidade de um professor para outro
     */
    public function copiarDisponibilidade(int $professorOrigemId, int $professorDestinoId): bool
    {
        try {
            DB::beginTransaction();

            // Remove disponibilidades antigas do destino
            ProfessorDisponibilidade::where('professor_id', $professorDestinoId)->delete();

            // Copia disponibilidades da origem
            $disponibilidades = ProfessorDisponibilidade::where('professor_id', $professorOrigemId)->get();

            foreach ($disponibilidades as $disp) {
                ProfessorDisponibilidade::create([
                    'professor_id' => $professorDestinoId,
                    'dia_semana' => $disp->dia_semana,
                    'hora_inicio' => $disp->hora_inicio,
                    'hora_fim' => $disp->hora_fim,
                    'preferencia' => $disp->preferencia,
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao copiar disponibilidade: {$e->getMessage()}");
            return false;
        }
    }
}
