@extends('layouts.app')

@section('title', 'Novo Curso')

@section('page-title', 'Cadastrar Novo Curso')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('cursos.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="nome" class="form-label">Nome do Curso <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                           id="nome" name="nome" value="{{ old('nome') }}" required>
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="codigo" class="form-label">Código <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                           id="codigo" name="codigo" value="{{ old('codigo') }}" required>
                    @error('codigo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="duracao_semestres" class="form-label">Duração em Semestres <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('duracao_semestres') is-invalid @enderror" 
                           id="duracao_semestres" name="duracao_semestres" value="{{ old('duracao_semestres') }}" 
                           min="1" required>
                    @error('duracao_semestres')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="carga_horaria_total" class="form-label">Carga Horária Total (horas) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('carga_horaria_total') is-invalid @enderror" 
                           id="carga_horaria_total" name="carga_horaria_total" value="{{ old('carga_horaria_total') }}" 
                           min="1" required>
                    @error('carga_horaria_total')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Salvar
                </button>
                <a href="{{ route('cursos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
