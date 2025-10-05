@extends('layouts.app')

@section('title', 'Disciplinas')

@section('page-title', 'Disciplinas')

@section('page-actions')
<a href="{{ route('disciplines.create') }}" class="btn btn-primary btn-custom">
    <i class="fas fa-plus me-2"></i>Nova Disciplina
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if(count($disciplines) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Carga Horária</th>
                            <th>Créditos</th>
                            <th>Tipo</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($disciplines as $discipline)
                        <tr>
                            <td>{{ $discipline['id'] }}</td>
                            <td><code>{{ $discipline['code'] }}</code></td>
                            <td>{{ $discipline['name'] }}</td>
                            <td>{{ $discipline['workload_hours'] }}h ({{ $discipline['weekly_hours'] }}h/semana)</td>
                            <td><strong>{{ $discipline['credits'] }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $discipline['type'] == 'obrigatória' ? 'success' : ($discipline['type'] == 'eletiva' ? 'info' : 'warning') }}">
                                    {{ ucfirst($discipline['type']) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="action-buttons-container">
                                    <a href="{{ route('disciplines.edit', $discipline['id']) }}" class="btn btn-outline-primary action-btn" title="Editar Disciplina">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('disciplines.destroy', $discipline['id']) }}" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta disciplina?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger action-btn" title="Excluir Disciplina">
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
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhuma disciplina cadastrada</h5>
                <p class="text-muted">Clique no botão "Nova Disciplina" para adicionar a primeira disciplina.</p>
                <a href="{{ route('disciplines.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cadastrar Primeira Disciplina
                </a>
            </div>
        @endif
    </div>
</div>
@endsection