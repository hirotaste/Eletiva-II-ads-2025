@extends('layouts.app')

@section('title', 'Preferências de Disciplinas')

@section('page-title')
    <i class="fas fa-book-open me-2"></i>Gerenciar Preferências de Disciplinas
@endsection

@section('content')
<div class="container-fluid">
    <!-- Seleção de Professor -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('professor-disciplina.index') }}" class="row g-3">
                <div class="col-md-8">
                    <label for="professor_id" class="form-label fw-bold">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Selecione o Professor
                    </label>
                    @if(auth()->user()->isProfessor())
                        <input type="text" class="form-control" value="{{ $professorSelecionado->nome ?? 'Professor' }}" readonly>
                        <input type="hidden" name="professor_id" value="{{ $professorSelecionado->id ?? '' }}">
                    @else
                        <select name="professor_id" id="professor_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Selecione um professor --</option>
                            @foreach($professores as $prof)
                                <option value="{{ $prof->id }}" {{ $professorSelecionado && $professorSelecionado->id == $prof->id ? 'selected' : '' }}>
                                    {{ $prof->nome }} - {{ $prof->email }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
                @if(!auth()->user()->isProfessor())
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>

    @if($professorSelecionado)
        <!-- Estatísticas -->
        @if($estatisticas)
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h3 class="mb-0">{{ $estatisticas['total'] }}</h3>
                        <small>Total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h3 class="mb-0">{{ $estatisticas['muito_alta'] }}</h3>
                        <small>Muito Alta</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h3 class="mb-0">{{ $estatisticas['alta'] }}</h3>
                        <small>Alta</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center bg-warning text-white">
                    <div class="card-body">
                        <h3 class="mb-0">{{ $estatisticas['media'] }}</h3>
                        <small>Média</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center bg-secondary text-white">
                    <div class="card-body">
                        <h3 class="mb-0">{{ $estatisticas['baixa'] }}</h3>
                        <small>Baixa</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center bg-danger text-white">
                    <div class="card-body">
                        <h3 class="mb-0">{{ $estatisticas['muito_baixa'] }}</h3>
                        <small>Muito Baixa</small>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Tabela de Disciplinas com Preferências -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Disciplinas de {{ $professorSelecionado->nome }}
                </h5>
                @if($disciplinasDisponiveis->count() > 0)
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdicionarDisciplina">
                    <i class="fas fa-plus me-2"></i>Adicionar Disciplina
                </button>
                @endif
            </div>
            <div class="card-body">
                @if($preferencias->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Código</th>
                                <th>Disciplina</th>
                                <th>Carga Horária</th>
                                <th width="200">Preferência</th>
                                <th width="150" class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($preferencias as $index => $pref)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="badge bg-secondary">{{ $pref->disciplina->codigo }}</span></td>
                                <td>
                                    <strong>{{ $pref->disciplina->nome }}</strong>
                                    <br>
                                    <small class="text-muted">{{ ucfirst($pref->disciplina->tipo) }}</small>
                                </td>
                                <td>{{ $pref->disciplina->carga_horaria }}h</td>
                                <td>
                                    <form method="POST" action="{{ route('professor-disciplina.update', $pref->id) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group input-group-sm">
                                            <select name="preferencia" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="1" {{ $pref->preferencia == 1 ? 'selected' : '' }}>⭐ Muito Baixa</option>
                                                <option value="2" {{ $pref->preferencia == 2 ? 'selected' : '' }}>⭐⭐ Baixa</option>
                                                <option value="3" {{ $pref->preferencia == 3 ? 'selected' : '' }}>⭐⭐⭐ Média</option>
                                                <option value="4" {{ $pref->preferencia == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ Alta</option>
                                                <option value="5" {{ $pref->preferencia == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ Muito Alta</option>
                                            </select>
                                        </div>
                                    </form>
                                    <span class="badge bg-{{ $pref->preferencia_cor }} mt-1">{{ $pref->preferencia_texto }}</span>
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('professor-disciplina.destroy', $pref->id) }}" class="d-inline" onsubmit="return confirm('Deseja realmente remover esta preferência?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Remover">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <p class="mb-0">Este professor ainda não possui disciplinas cadastradas.</p>
                    @if($disciplinasDisponiveis->count() > 0)
                    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalAdicionarDisciplina">
                        <i class="fas fa-plus me-2"></i>Adicionar Primeira Disciplina
                    </button>
                    @endif
                </div>
                @endif
            </div>
        </div>
    @else
        <!-- Mensagem quando nenhum professor está selecionado -->
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-user-tie fa-5x text-muted mb-3"></i>
                <h4 class="text-muted">Selecione um professor para exibir as preferências</h4>
                <p class="text-muted">Use o campo acima para escolher o professor que deseja gerenciar</p>
            </div>
        </div>
    @endif
</div>

<!-- Modal Adicionar Disciplina -->
@if($professorSelecionado && $disciplinasDisponiveis->count() > 0)
<div class="modal fade" id="modalAdicionarDisciplina" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('professor-disciplina.store') }}">
                @csrf
                <input type="hidden" name="professor_id" value="{{ $professorSelecionado->id }}">
                
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Adicionar Disciplina
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="disciplina_id" class="form-label">Disciplina *</label>
                        <select name="disciplina_id" id="disciplina_id" class="form-select" required>
                            <option value="">-- Selecione uma disciplina --</option>
                            @foreach($disciplinasDisponiveis as $disc)
                                <option value="{{ $disc->id }}">
                                    {{ $disc->codigo }} - {{ $disc->nome }} ({{ $disc->carga_horaria }}h)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="preferencia" class="form-label">Nível de Preferência *</label>
                        <select name="preferencia" id="preferencia" class="form-select" required>
                            <option value="">-- Selecione --</option>
                            <option value="5">⭐⭐⭐⭐⭐ Muito Alta</option>
                            <option value="4">⭐⭐⭐⭐ Alta</option>
                            <option value="3" selected>⭐⭐⭐ Média</option>
                            <option value="2">⭐⭐ Baixa</option>
                            <option value="1">⭐ Muito Baixa</option>
                        </select>
                        <small class="text-muted">Indica o quanto o professor prefere lecionar esta disciplina</small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Adicionar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@section('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
        border-radius: 0.5rem;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: #495057;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
</style>
@endsection
