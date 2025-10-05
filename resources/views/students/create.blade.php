@extends('layouts.app')

@section('title', 'Novo Estudante')

@section('page-title', 'Cadastrar Novo Estudante')

@section('page-actions')
<a href="{{ route('students.index') }}" class="btn btn-secondary btn-custom">
    <i class="fas fa-arrow-left me-2"></i>Voltar
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('students.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="registration_number" class="form-label">Matrícula</label>
                        <input type="text" class="form-control" id="registration_number" name="registration_number" required 
                               placeholder="Ex: 2023001001">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               placeholder="Ex: Pedro Henrique">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               placeholder="estudante@estudante.edu.br">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Selecione...</option>
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                            <option value="graduado">Graduado</option>
                            <option value="suspenso">Suspenso</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="gpa" class="form-label">GPA (0-10)</label>
                        <input type="number" class="form-control" id="gpa" name="gpa" step="0.1" min="0" max="10" 
                               placeholder="8.5">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Salvar Estudante
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection