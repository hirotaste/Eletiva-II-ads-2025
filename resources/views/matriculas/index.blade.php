@extends('layouts.app')

@section('title', 'Matrículas')

@section('page-title', 'Gerenciar Matrículas')

@section('page-actions')
<a href="{{ route('matriculas.create') }}" class="btn btn-primary btn-custom">
    <i class="fas fa-plus me-2"></i>Nova Matrícula
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if($matriculas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Turma</th>
                            <th>Disciplina</th>
                            <th>Período</th>
                            <th>Status</th>
                            <th>Dependência</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matriculas as $matricula)
                        <tr>
                            <td>{{ $matricula->aluno->nome ?? 'N/A' }}</td>
                            <td><strong>{{ $matricula->turma->codigo ?? 'N/A' }}</strong></td>
                            <td>{{ $matricula->turma->disciplina->nome ?? 'N/A' }}</td>
                            <td>{{ $matricula->turma->periodoLetivo->nome_completo ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'matriculado' => 'success',
                                        'trancado' => 'warning',
                                        'cancelado' => 'danger'
                                    ];
                                    $color = $statusColors[$matricula->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ ucfirst($matricula->status ?? 'N/A') }}
                                </span>
                            </td>
                            <td>
                                @if($matricula->is_dependencia)
                                    <span class="badge bg-warning">Sim</span>
                                @else
                                    <span class="badge bg-secondary">Não</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="action-buttons-container">
                                    <a href="{{ route('matriculas.edit', $matricula->id) }}" class="btn btn-outline-primary action-btn" title="Editar Matrícula">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('matriculas.destroy', $matricula->id) }}" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta matrícula?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger action-btn" title="Excluir Matrícula">
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
                <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhuma matrícula cadastrada</h5>
                <p class="text-muted">Clique no botão "Nova Matrícula" para adicionar a primeira matrícula.</p>
                <a href="{{ route('matriculas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cadastrar Primeira Matrícula
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
