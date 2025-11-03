@extends('layouts.app')

@section('title', 'Turmas')

@section('page-title', 'Gerenciar Turmas')

@section('page-actions')
<a href="{{ route('turmas.create') }}" class="btn btn-primary btn-custom">
    <i class="fas fa-plus me-2"></i>Nova Turma
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if($turmas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Período</th>
                            <th>Disciplina</th>
                            <th>Professor</th>
                            <th>Vagas</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($turmas as $turma)
                        <tr>
                            <td><strong>{{ $turma->codigo }}</strong></td>
                            <td>{{ $turma->periodoLetivo->nome_completo ?? 'N/A' }}</td>
                            <td>{{ $turma->disciplina->nome ?? 'N/A' }}</td>
                            <td>{{ $turma->professor->nome ?? 'Não atribuído' }}</td>
                            <td>
                                <span class="badge bg-{{ $turma->temVagasDisponiveis() ? 'success' : 'danger' }}">
                                    {{ $turma->vagas_ocupadas }}/{{ $turma->vagas_total }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="action-buttons-container">
                                    <a href="{{ route('turmas.edit', $turma->id) }}" class="btn btn-outline-primary action-btn" title="Editar Turma">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('turmas.destroy', $turma->id) }}" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta turma?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger action-btn" title="Excluir Turma">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users-class fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhuma turma cadastrada</h5>
                <p class="text-muted">Clique no botão "Nova Turma" para adicionar a primeira turma.</p>
                <a href="{{ route('turmas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cadastrar Primeira Turma
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
