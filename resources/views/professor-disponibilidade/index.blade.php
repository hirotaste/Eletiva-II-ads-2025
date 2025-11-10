@extends('layouts.app')

@section('title', 'Disponibilidade de Professores')

@section('page-title')
    <i class="fas fa-calendar-check me-2"></i>Disponibilidade de Professores
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Cabeçalho com ações --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    @if(auth()->user()->isAdmin())
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Selecione um Professor</h5>
                    @else
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Minha Disponibilidade</h5>
                    @endif
                </div>
                <div class="col-md-6 text-end">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('disponibilidade.configuracao') }}" class="btn btn-secondary">
                            <i class="fas fa-cog me-1"></i>Configuração Geral
                        </a>
                    @endif
                </div>
            </div>

            @if(auth()->user()->isAdmin())
                <hr>
                <form method="GET" action="{{ route('disponibilidade.index') }}">
                    <div class="row">
                        <div class="col-md-8">
                            <select name="professor_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Selecione um professor --</option>
                                @foreach($professores as $prof)
                                    <option value="{{ $prof->id }}" {{ $professorSelecionado == $prof->id ? 'selected' : '' }}>
                                        {{ $prof->nome }} 
                                        ({{ $prof->total_horas ?? 0 }}h disponíveis)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            @if($professorSelecionado)
                                <a href="{{ route('disponibilidade.edit', $professorSelecionado) }}" 
                                   class="btn btn-primary w-100">
                                    <i class="fas fa-edit me-1"></i>Editar Disponibilidade
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            @else
                @if($professor)
                    <div class="mt-3">
                        <a href="{{ route('disponibilidade.edit', $professor->id) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>Editar Minha Disponibilidade
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Grade de Disponibilidade --}}
    @if($grade)
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Grade de Disponibilidade - {{ $professor->nome }}
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 80px;">Horário</th>
                                @foreach($diasSemana as $diaNum => $diaNome)
                                    <th class="text-center">{{ $diaNome }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @for($hora = 0; $hora < 24; $hora++)
                                <tr>
                                    <td class="text-center fw-bold bg-light">
                                        {{ sprintf('%02d:00', $hora) }}
                                    </td>
                                    @foreach($diasSemana as $diaNum => $diaNome)
                                        @php
                                            $disponivel = $grade[$hora][$diaNum]['disponivel'] ?? false;
                                            $preferencia = $grade[$hora][$diaNum]['preferencia'] ?? null;
                                            
                                            $bgColor = $disponivel ? 'bg-success' : 'bg-danger';
                                            $icon = $disponivel ? 'fa-check' : 'fa-times';
                                            $title = $disponivel 
                                                ? "Disponível (Preferência: $preferencia)" 
                                                : 'Indisponível';
                                        @endphp
                                        <td class="text-center {{ $bgColor }} text-white" 
                                            style="padding: 8px;" 
                                            title="{{ $title }}">
                                            <i class="fas {{ $icon }}"></i>
                                            @if($disponivel && $preferencia)
                                                <small class="d-block" style="font-size: 0.7rem;">
                                                    Pref: {{ $preferencia }}
                                                </small>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <span class="badge bg-success me-2">
                            <i class="fas fa-check me-1"></i>Disponível
                        </span>
                        <span class="badge bg-danger">
                            <i class="fas fa-times me-1"></i>Indisponível
                        </span>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Pref: Nível de preferência (1-5)
                        </small>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Selecione um professor para exibir a grade</h4>
                <p class="text-muted">Use o seletor acima para escolher um professor e visualizar sua disponibilidade.</p>
            </div>
        </div>
    @endif
</div>

<style>
    .table-bordered td, .table-bordered th {
        border: 1px solid #dee2e6 !important;
    }
    
    .table-hover tbody tr:hover td {
        opacity: 0.8;
    }
</style>
@endsection
