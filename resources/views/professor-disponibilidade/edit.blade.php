@extends('layouts.app')

@section('title', 'Editar Disponibilidade')

@section('page-title')
    <i class="fas fa-edit me-2"></i>Editar Disponibilidade - {{ $professor->nome }}
@endsection

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('disponibilidade.update', $professor->id) }}" id="formDisponibilidade">
        @csrf
        @method('PUT')

        {{-- Barra de Ferramentas --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="fas fa-user-tie me-2"></i>{{ $professor->nome }}
                        </h5>
                        <small class="text-muted">{{ $professor->email }}</small>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="button" class="btn btn-success me-2" onclick="marcarTodos(true)">
                            <i class="fas fa-check-square me-1"></i>Marcar Todos
                        </button>
                        <button type="button" class="btn btn-warning me-2" onclick="marcarTodos(false)">
                            <i class="fas fa-square me-1"></i>Desmarcar Todos
                        </button>
                        <a href="{{ route('disponibilidade.index', ['professor_id' => $professor->id]) }}" 
                           class="btn btn-secondary me-2">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Salvar
                        </button>
                    </div>
                </div>

                <hr>

                {{-- Ferramentas de Seleção --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group me-3" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="marcarDia('todos')">
                                Marcar Semana Completa
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="marcarDia('util')">
                                Seg-Sex
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="marcarDia('fds')">
                                Fim de Semana
                            </button>
                        </div>

                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="marcarPeriodo('manha')">
                                Manhã (7h-12h)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="marcarPeriodo('tarde')">
                                Tarde (13h-18h)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="marcarPeriodo('noite')">
                                Noite (19h-22h)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grade Interativa --}}
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Grade de Disponibilidade
                </h5>
                <small>Clique nas células para marcar/desmarcar. Verde = Disponível, Vermelho = Indisponível</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center sticky-col" style="width: 80px;">Horário</th>
                                @foreach($diasSemana as $diaNum => $diaNome)
                                    <th class="text-center" data-dia="{{ $diaNum }}">
                                        {{ $diaNome }}
                                        <br>
                                        <button type="button" class="btn btn-xs btn-outline-secondary mt-1" 
                                                onclick="marcarColuna({{ $diaNum }})">
                                            <i class="fas fa-check-square"></i>
                                        </button>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @for($hora = 0; $hora < 24; $hora++)
                                <tr>
                                    <td class="text-center fw-bold bg-light sticky-col">
                                        {{ sprintf('%02d:00', $hora) }}
                                        <button type="button" class="btn btn-xs btn-outline-secondary" 
                                                onclick="marcarLinha({{ $hora }})">
                                            <i class="fas fa-check-square"></i>
                                        </button>
                                    </td>
                                    @foreach($diasSemana as $diaNum => $diaNome)
                                        @php
                                            $disponivel = $grade[$hora][$diaNum]['disponivel'] ?? false;
                                            $preferencia = $grade[$hora][$diaNum]['preferencia'] ?? 3;
                                        @endphp
                                        <td class="text-center p-0 celula-grade" 
                                            data-hora="{{ $hora }}" 
                                            data-dia="{{ $diaNum }}"
                                            data-disponivel="{{ $disponivel ? '1' : '0' }}"
                                            data-preferencia="{{ $preferencia }}"
                                            onclick="toggleCelula(this)"
                                            style="cursor: pointer; padding: 20px !important; position: relative;">
                                            
                                            <div class="conteudo-celula" style="min-height: 40px;">
                                                <i class="fas icone-celula" style="font-size: 1.2rem;"></i>
                                            </div>

                                            {{-- Input hidden para enviar dados --}}
                                            <input type="hidden" 
                                                   name="grade[{{ $diaNum }}][{{ $hora }}][disponivel]" 
                                                   value="{{ $disponivel ? '1' : '0' }}"
                                                   class="input-disponivel">
                                            <input type="hidden" 
                                                   name="grade[{{ $diaNum }}][{{ $hora }}][preferencia]" 
                                                   value="{{ $preferencia }}"
                                                   class="input-preferencia">
                                        </td>
                                    @endforeach
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Dica:</strong> Clique nas células para alternar entre disponível/indisponível. 
                    Use os botões acima para marcar períodos completos rapidamente.
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .sticky-col {
        position: sticky;
        left: 0;
        z-index: 10;
        background-color: #f8f9fa !important;
    }

    .celula-grade {
        transition: all 0.2s ease;
        border: 2px solid #dee2e6 !important;
    }

    .celula-grade:hover {
        transform: scale(1.05);
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        z-index: 5;
    }

    .celula-grade[data-disponivel="1"] {
        background-color: #28a745 !important;
        color: white;
    }

    .celula-grade[data-disponivel="0"] {
        background-color: #dc3545 !important;
        color: white;
    }

    .celula-grade[data-disponivel="1"] .icone-celula::before {
        content: "\f00c"; /* fa-check */
    }

    .celula-grade[data-disponivel="0"] .icone-celula::before {
        content: "\f00d"; /* fa-times */
    }

    .btn-xs {
        padding: 0.15rem 0.3rem;
        font-size: 0.7rem;
    }
</style>

<script>
    // Toggle individual cell
    function toggleCelula(celula) {
        const disponivel = celula.dataset.disponivel === '1';
        const novoValor = disponivel ? '0' : '1';
        
        celula.dataset.disponivel = novoValor;
        celula.querySelector('.input-disponivel').value = novoValor;
        
        // Animação
        celula.style.transform = 'scale(0.95)';
        setTimeout(() => {
            celula.style.transform = 'scale(1)';
        }, 100);
    }

    // Marcar todos
    function marcarTodos(disponivel) {
        const valor = disponivel ? '1' : '0';
        document.querySelectorAll('.celula-grade').forEach(celula => {
            celula.dataset.disponivel = valor;
            celula.querySelector('.input-disponivel').value = valor;
        });
    }

    // Marcar coluna inteira (dia)
    function marcarColuna(dia) {
        document.querySelectorAll(`.celula-grade[data-dia="${dia}"]`).forEach(celula => {
            celula.dataset.disponivel = '1';
            celula.querySelector('.input-disponivel').value = '1';
        });
    }

    // Marcar linha inteira (hora)
    function marcarLinha(hora) {
        document.querySelectorAll(`.celula-grade[data-hora="${hora}"]`).forEach(celula => {
            celula.dataset.disponivel = '1';
            celula.querySelector('.input-disponivel').value = '1';
        });
    }

    // Marcar dias específicos
    function marcarDia(tipo) {
        let dias = [];
        if (tipo === 'todos') {
            dias = [1, 2, 3, 4, 5, 6, 7];
        } else if (tipo === 'util') {
            dias = [2, 3, 4, 5, 6]; // Seg-Sex
        } else if (tipo === 'fds') {
            dias = [1, 7]; // Dom-Sab
        }

        dias.forEach(dia => marcarColuna(dia));
    }

    // Marcar períodos
    function marcarPeriodo(periodo) {
        let horaInicio, horaFim;
        
        if (periodo === 'manha') {
            horaInicio = 7; horaFim = 12;
        } else if (periodo === 'tarde') {
            horaInicio = 13; horaFim = 18;
        } else if (periodo === 'noite') {
            horaInicio = 19; horaFim = 22;
        }

        for (let hora = horaInicio; hora < horaFim; hora++) {
            document.querySelectorAll(`.celula-grade[data-hora="${hora}"]`).forEach(celula => {
                celula.dataset.disponivel = '1';
                celula.querySelector('.input-disponivel').value = '1';
            });
        }
    }

    // Confirmação antes de sair sem salvar
    let formModificado = false;
    document.getElementById('formDisponibilidade').addEventListener('change', () => {
        formModificado = true;
    });

    window.addEventListener('beforeunload', (e) => {
        if (formModificado) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    document.getElementById('formDisponibilidade').addEventListener('submit', () => {
        formModificado = false;
    });
</script>
@endsection
