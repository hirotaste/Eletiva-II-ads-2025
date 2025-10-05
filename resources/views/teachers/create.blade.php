@extends('layouts.app')

@section('title', 'Novo Professor')

@section('page-title', 'Cadastrar Novo Professor')

@section('page-actions')
<a href="{{ route('teachers.index') }}" class="btn btn-secondary btn-custom">
    <i class="fas fa-arrow-left me-2"></i>Voltar
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('teachers.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               placeholder="Ex: Dr. João Silva">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               placeholder="professor@universidade.edu.br">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="specialization" class="form-label">Especialização</label>
                        <input type="text" class="form-control" id="specialization" name="specialization" required 
                               placeholder="Ex: Engenharia de Software">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="employment_type" class="form-label">Tipo de Vínculo</label>
                        <select class="form-select" id="employment_type" name="employment_type" required>
                            <option value="">Selecione...</option>
                            <option value="integral">Integral</option>
                            <option value="meio periodo">Meio Período</option>
                            <option value="contratado">Contratado</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Salvar Professor
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5><i class="fas fa-info-circle me-2"></i>Informações</h5>
    </div>
    <div class="card-body">
        <ul class="list-unstyled mb-0">
            <li><i class="fas fa-check text-success me-2"></i>Todos os campos são obrigatórios</li>
            <li><i class="fas fa-check text-success me-2"></i>O email deve ser único no sistema</li>
            <li><i class="fas fa-check text-success me-2"></i>Os dados serão armazenados temporariamente na sessão</li>
        </ul>
    </div>
</div>
@endsection