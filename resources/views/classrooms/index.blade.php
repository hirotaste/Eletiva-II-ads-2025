@extends('layouts.app')

@section('title', 'Salas de Aula')

@section('page-title', 'Salas de Aula')

@section('page-actions')
<a href="{{ route('classrooms.create') }}" class="btn btn-primary btn-custom">
    <i class="fas fa-plus me-2"></i>Nova Sala
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if(count($classrooms) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Prédio</th>
                            <th>Capacidade</th>
                            <th>Tipo</th>
                            <th>Acessibilidade</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classrooms as $classroom)
                        <tr>
                            <td>{{ $classroom['id'] }}</td>
                            <td><code>{{ $classroom['code'] }}</code></td>
                            <td>{{ $classroom['building'] }}</td>
                            <td><strong>{{ $classroom['capacity'] }}</strong> lugares</td>
                            <td>
                                <span class="badge bg-{{ $classroom['type'] == 'aula teorica' ? 'primary' : ($classroom['type'] == 'laboratorio' ? 'info' : 'warning') }}">
                                    {{ ucfirst($classroom['type']) }}
                                </span>
                            </td>
                            <td>
                                @if($classroom['has_accessibility'])
                                    <i class="fas fa-wheelchair text-success" title="Possui acessibilidade"></i>
                                @else
                                    <i class="fas fa-times text-muted" title="Não possui acessibilidade"></i>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('classrooms.edit', $classroom['id']) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('classrooms.destroy', $classroom['id']) }}" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta sala?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
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
                <a href="{{ route('classrooms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cadastrar Primeira Sala
                </a>
            </div>
        @endif
    </div>
</div>
@endsection