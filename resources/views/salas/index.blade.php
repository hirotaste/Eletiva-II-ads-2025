@extends('layouts.app')

@section('title', 'Salas')

@section('page-title', 'Gerenciar Salas')

@section('page-actions')
<a href="{{ route('salas.create') }}" class="btn btn-primary btn-custom">
    <i class="fas fa-plus me-2"></i>Nova Sala
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if($salas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Capacidade</th>
                            <th>Recursos</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salas as $sala)
                        <tr>
                            <td><strong>{{ $sala->codigo }}</strong></td>
                            <td>{{ $sala->nome }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $sala->tipo ? ucfirst(str_replace('_', ' ', $sala->tipo)) : 'Não definido' }}
                                </span>
                            </td>
                            <td class="text-center">{{ $sala->capacidade }}</td>
                            <td>
                                <small class="text-muted">{{ $sala->recursos_disponiveis }}</small>
                            </td>
                            <td class="text-end">
                                <div class="action-buttons-container">
                                    <a href="{{ route('salas.edit', $sala->id) }}" class="btn btn-outline-primary action-btn" title="Editar Sala">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('salas.destroy', $sala->id) }}" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta sala?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger action-btn" title="Excluir Sala">
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
                <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhuma sala cadastrada</h5>
                <p class="text-muted">Clique no botão "Nova Sala" para adicionar a primeira sala.</p>
                <a href="{{ route('salas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cadastrar Primeira Sala
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
