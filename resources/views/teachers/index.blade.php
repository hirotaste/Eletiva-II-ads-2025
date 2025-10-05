@extends('layouts.app')

@section('title', 'Professores')

@section('page-title', 'Professores')

@section('page-actions')
<a href="{{ route('teachers.create') }}" class="btn btn-primary btn-custom">
    <i class="fas fa-plus me-2"></i>Novo Professor
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if(count($teachers) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Especialização</th>
                            <th>Tipo de Vínculo</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher['id'] }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                         style="width: 40px; height: 40px; font-size: 16px;">
                                        {{ strtoupper(substr($teacher['name'], 0, 1)) }}
                                    </div>
                                    {{ $teacher['name'] }}
                                </div>
                            </td>
                            <td>{{ $teacher['email'] }}</td>
                            <td>
                                <span class="badge bg-info">{{ $teacher['specialization'] }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $teacher['employment_type'] == 'integral' ? 'success' : 'warning' }}">
                                    {{ ucfirst($teacher['employment_type']) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="action-buttons-container">
                                    <a href="{{ route('teachers.edit', $teacher['id']) }}" class="btn btn-outline-primary action-btn" title="Editar Professor">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('teachers.destroy', $teacher['id']) }}" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir este professor?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger action-btn" title="Excluir Professor">
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
                <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhum professor cadastrado</h5>
                <p class="text-muted">Clique no botão "Novo Professor" para adicionar o primeiro professor.</p>
                <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cadastrar Primeiro Professor
                </a>
            </div>
        @endif
    </div>
</div>
@endsection