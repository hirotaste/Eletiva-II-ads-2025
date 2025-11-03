@extends('layouts.app')

@section('title', 'Editar Matrícula')

@section('page-title', 'Editar Matrícula')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('matriculas.update', $matricula->id) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Aluno</label>
                <input type="text" class="form-control" value="{{ $matricula->aluno->matricula }} - {{ $matricula->aluno->nome }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Turma</label>
                <input type="text" class="form-control" 
                       value="{{ $matricula->turma->codigo }} - {{ $matricula->turma->disciplina->nome ?? 'N/A' }}" readonly>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="matriculado" {{ old('status', $matricula->status) == 'matriculado' ? 'selected' : '' }}>Matriculado</option>
                        <option value="trancado" {{ old('status', $matricula->status) == 'trancado' ? 'selected' : '' }}>Trancado</option>
                        <option value="cancelado" {{ old('status', $matricula->status) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Dependência</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="is_dependencia" 
                               name="is_dependencia" value="1" {{ old('is_dependencia', $matricula->is_dependencia) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_dependencia">
                            Esta matrícula é uma dependência
                        </label>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <small>Data da matrícula: {{ $matricula->data_matricula->format('d/m/Y H:i') }}</small>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Atualizar
                </button>
                <a href="{{ route('matriculas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
