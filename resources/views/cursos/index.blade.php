@extends('layouts.app')

@section('title', 'Cursos')

@section('page-title', 'Gerenciar Cursos')

@section('page-actions')
<a href="{{ route('cursos.create') }}" class="btn btn-primary btn-custom">
    <i class="fas fa-plus me-2"></i>Novo Curso
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if($cursos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Duração (Semestres)</th>
                            <th>Carga Horária Total</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cursos as $curso)
                        <tr>
                            <td><strong>{{ $curso->codigo }}</strong></td>
                            <td>{{ $curso->nome }}</td>
                            <td class="text-center">{{ $curso->duracao_semestres }}</td>
                            <td class="text-center">{{ $curso->carga_horaria_total }}h</td>
                            <td>
                                <span class="badge bg-{{ $curso->ativo ? 'success' : 'secondary' }}">
                                    {{ $curso->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="action-buttons-container">
                                    <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-outline-primary action-btn" title="Editar Curso">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('cursos.destroy', $curso->id) }}" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir este curso?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger action-btn" title="Excluir Curso">
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
                <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhum curso cadastrado</h5>
                <p class="text-muted">Clique no botão "Novo Curso" para adicionar o primeiro curso.</p>
                <a href="{{ route('cursos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cadastrar Primeiro Curso
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
