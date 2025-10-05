@extends('layouts.app')

@section('title', 'Editar Estudante')

@section('page-title', 'Editar Estudante')

@section('page-actions')
<a href="{{ route('students.index') }}" class="btn btn-secondary btn-custom">
    <i class="fas fa-arrow-left me-2"></i>Voltar
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('students.update', $student['id']) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="registration_number" class="form-label">Matrícula</label>
                        <input type="text" class="form-control" id="registration_number" name="registration_number" required 
                               value="{{ $student['registration_number'] }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               value="{{ $student['name'] }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               value="{{ $student['email'] }}">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="ativo" {{ $student['status'] == 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ $student['status'] == 'inativo' ? 'selected' : '' }}>Inativo</option>
                            <option value="graduado" {{ $student['status'] == 'graduado' ? 'selected' : '' }}>Graduado</option>
                            <option value="suspenso" {{ $student['status'] == 'suspenso' ? 'selected' : '' }}>Suspenso</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="gpa" class="form-label">GPA (0-10)</label>
                        <input type="number" class="form-control" id="gpa" name="gpa" step="0.1" min="0" max="10" 
                               value="{{ $student['gpa'] ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Atualizar Estudante
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection