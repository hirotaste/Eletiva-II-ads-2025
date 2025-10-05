@extends('layouts.app')

@section('title', 'Estudantes')

@section('page-title', 'Estudantes')

@section('page-actions')
<a href="{{ route('students.create') }}" class="btn btn-primary btn-custom">
    <i class="fas fa-plus me-2"></i>Novo Estudante
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if(count($students) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Matrícula</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>GPA</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{ $student['id'] }}</td>
                            <td><code>{{ $student['registration_number'] }}</code></td>
                            <td>{{ $student['name'] }}</td>
                            <td>{{ $student['email'] }}</td>
                            <td>
                                <span class="badge bg-{{ $student['status'] == 'ativo' ? 'success' : 'warning' }}">
                                    {{ ucfirst($student['status']) }}
                                </span>
                            </td>
                            <td><strong>{{ $student['gpa'] }}</strong></td>
                            <td class="text-end">
                                <div class="action-buttons-container">
                                    <a href="{{ route('students.edit', $student['id']) }}" class="btn btn-outline-primary action-btn" title="Editar Estudante">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('students.destroy', $student['id']) }}" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir este estudante?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger action-btn" title="Excluir Estudante">
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
                <h5 class="text-muted">Nenhum estudante cadastrado</h5>
                <p class="text-muted">Clique no botão "Novo Estudante" para adicionar o primeiro estudante.</p>
                <a href="{{ route('students.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cadastrar Primeiro Estudante
                </a>
            </div>
        @endif
    </div>
</div>
@endsection