@extends('layouts.app')

@section('title', 'Configuração de Horários')

@section('page-title')
    <i class="fas fa-cog me-2"></i>Configuração Geral de Horários
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('disponibilidade.salvar-configuracao') }}">
        @csrf

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Horários de Funcionamento da Instituição
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Configure os dias e horários em que a instituição funciona. 
                    Você pode adicionar vários períodos para o mesmo dia (ex: manhã, tarde, noite).
                </div>

                @foreach($diasSemana as $diaNum => $diaNome)
                    <div class="card mb-3 dia-config" data-dia="{{ $diaNum }}">
                        <div class="card-header" style="background-color: #f8f9fa;">
                            <div class="form-check form-switch">
                                <input class="form-check-input dia-ativo-check" 
                                       type="checkbox" 
                                       name="configuracoes[{{ $diaNum }}][ativo]"
                                       id="dia_{{ $diaNum }}_ativo"
                                       value="1"
                                       {{ isset($configuracoes[$diaNum]) && $configuracoes[$diaNum]['ativo'] ? 'checked' : '' }}
                                       onchange="toggleDia({{ $diaNum }})">
                                <label class="form-check-label fw-bold" for="dia_{{ $diaNum }}_ativo">
                                    {{ $diaNome }}
                                </label>
                            </div>
                            <input type="hidden" name="configuracoes[{{ $diaNum }}][dia_semana]" value="{{ $diaNum }}">
                        </div>
                        <div class="card-body horarios-container" id="horarios_{{ $diaNum }}" 
                             style="display: {{ isset($configuracoes[$diaNum]) && $configuracoes[$diaNum]['ativo'] ? 'block' : 'none' }}">
                            
                            <div class="horarios-lista">
                                @if(isset($configuracoes[$diaNum]['horarios']))
                                    @foreach($configuracoes[$diaNum]['horarios'] as $index => $horario)
                                        <div class="row mb-2 horario-item">
                                            <div class="col-md-3">
                                                <label class="form-label small">Hora Início</label>
                                                <input type="time" 
                                                       class="form-control form-control-sm" 
                                                       name="configuracoes[{{ $diaNum }}][horarios][{{ $index }}][hora_inicio]"
                                                       value="{{ substr($horario['inicio'], 0, 5) }}"
                                                       required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small">Hora Fim</label>
                                                <input type="time" 
                                                       class="form-control form-control-sm" 
                                                       name="configuracoes[{{ $diaNum }}][horarios][{{ $index }}][hora_fim]"
                                                       value="{{ substr($horario['fim'], 0, 5) }}"
                                                       required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">Descrição (opcional)</label>
                                                <input type="text" 
                                                       class="form-control form-control-sm" 
                                                       name="configuracoes[{{ $diaNum }}][horarios][{{ $index }}][descricao]"
                                                       value="{{ $horario['descricao'] ?? '' }}"
                                                       placeholder="Ex: Manhã, Tarde, Noite">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="removerHorario(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row mb-2 horario-item">
                                        <div class="col-md-3">
                                            <label class="form-label small">Hora Início</label>
                                            <input type="time" 
                                                   class="form-control form-control-sm" 
                                                   name="configuracoes[{{ $diaNum }}][horarios][0][hora_inicio]"
                                                   value="07:00">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Hora Fim</label>
                                            <input type="time" 
                                                   class="form-control form-control-sm" 
                                                   name="configuracoes[{{ $diaNum }}][horarios][0][hora_fim]"
                                                   value="22:00">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Descrição (opcional)</label>
                                            <input type="text" 
                                                   class="form-control form-control-sm" 
                                                   name="configuracoes[{{ $diaNum }}][horarios][0][descricao]"
                                                   placeholder="Ex: Integral">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="removerHorario(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <button type="button" 
                                    class="btn btn-sm btn-success mt-2" 
                                    onclick="adicionarHorario({{ $diaNum }})">
                                <i class="fas fa-plus me-1"></i>Adicionar Período
                            </button>
                        </div>
                    </div>
                @endforeach

                <div class="row mt-4">
                    <div class="col-12 text-end">
                        <a href="{{ route('disponibilidade.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Salvar Configuração
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Templates Rápidos --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-magic me-2"></i>Templates Rápidos</h6>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-outline-primary me-2" onclick="aplicarTemplate('padrao')">
                    <i class="fas fa-clock me-1"></i>Seg-Sex (7h-22h)
                </button>
                <button type="button" class="btn btn-outline-primary me-2" onclick="aplicarTemplate('comercial')">
                    <i class="fas fa-briefcase me-1"></i>Comercial (8h-18h)
                </button>
                <button type="button" class="btn btn-outline-primary" onclick="aplicarTemplate('integral')">
                    <i class="fas fa-sun me-1"></i>Integral (7h-23h)
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    let horarioIndex = {};

    // Inicializar contadores
    @foreach($diasSemana as $diaNum => $diaNome)
        horarioIndex[{{ $diaNum }}] = {{ isset($configuracoes[$diaNum]['horarios']) ? count($configuracoes[$diaNum]['horarios']) : 1 }};
    @endforeach

    function toggleDia(dia) {
        const checkbox = document.getElementById(`dia_${dia}_ativo`);
        const container = document.getElementById(`horarios_${dia}`);
        container.style.display = checkbox.checked ? 'block' : 'none';
    }

    function adicionarHorario(dia) {
        const container = document.querySelector(`#horarios_${dia} .horarios-lista`);
        const index = horarioIndex[dia]++;
        
        const html = `
            <div class="row mb-2 horario-item">
                <div class="col-md-3">
                    <label class="form-label small">Hora Início</label>
                    <input type="time" 
                           class="form-control form-control-sm" 
                           name="configuracoes[${dia}][horarios][${index}][hora_inicio]"
                           value="07:00"
                           required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Hora Fim</label>
                    <input type="time" 
                           class="form-control form-control-sm" 
                           name="configuracoes[${dia}][horarios][${index}][hora_fim]"
                           value="12:00"
                           required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Descrição (opcional)</label>
                    <input type="text" 
                           class="form-control form-control-sm" 
                           name="configuracoes[${dia}][horarios][${index}][descricao]"
                           placeholder="Ex: Manhã, Tarde, Noite">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" 
                            class="btn btn-sm btn-danger" 
                            onclick="removerHorario(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
    }

    function removerHorario(button) {
        const item = button.closest('.horario-item');
        item.remove();
    }

    function aplicarTemplate(tipo) {
        // Limpar todos primeiro
        document.querySelectorAll('.dia-ativo-check').forEach(cb => cb.checked = false);
        document.querySelectorAll('.horarios-container').forEach(c => c.style.display = 'none');

        let dias = [2, 3, 4, 5, 6]; // Seg-Sex por padrão
        let periodos = [];

        if (tipo === 'padrao') {
            periodos = [
                { inicio: '07:00', fim: '12:00', desc: 'Manhã' },
                { inicio: '13:00', fim: '18:00', desc: 'Tarde' },
                { inicio: '19:00', fim: '22:00', desc: 'Noite' }
            ];
        } else if (tipo === 'comercial') {
            periodos = [
                { inicio: '08:00', fim: '12:00', desc: 'Manhã' },
                { inicio: '13:00', fim: '18:00', desc: 'Tarde' }
            ];
        } else if (tipo === 'integral') {
            periodos = [
                { inicio: '07:00', fim: '23:00', desc: 'Integral' }
            ];
        }

        dias.forEach(dia => {
            const checkbox = document.getElementById(`dia_${dia}_ativo`);
            checkbox.checked = true;
            toggleDia(dia);

            // Limpar horários existentes
            const container = document.querySelector(`#horarios_${dia} .horarios-lista`);
            container.innerHTML = '';

            // Adicionar novos períodos
            periodos.forEach((p, index) => {
                const html = `
                    <div class="row mb-2 horario-item">
                        <div class="col-md-3">
                            <label class="form-label small">Hora Início</label>
                            <input type="time" class="form-control form-control-sm" 
                                   name="configuracoes[${dia}][horarios][${index}][hora_inicio]"
                                   value="${p.inicio}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Hora Fim</label>
                            <input type="time" class="form-control form-control-sm" 
                                   name="configuracoes[${dia}][horarios][${index}][hora_fim]"
                                   value="${p.fim}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Descrição (opcional)</label>
                            <input type="text" class="form-control form-control-sm" 
                                   name="configuracoes[${dia}][horarios][${index}][descricao]"
                                   value="${p.desc}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-danger" onclick="removerHorario(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            });
        });
    }
</script>
@endsection
