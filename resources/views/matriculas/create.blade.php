@extends('layouts.app')

@section('title', 'Nova Matrícula')

@section('page-title', 'Cadastrar Nova Matrícula')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('matriculas.store') }}">
            @csrf
            
            <div class="mb-3">
                <label for="aluno_id" class="form-label">Aluno <span class="text-danger">*</span></label>
                <select class="form-select @error('aluno_id') is-invalid @enderror" id="aluno_id" name="aluno_id" required>
                    <option value="">Selecione...</option>
                    @foreach($alunos as $aluno)
                        <option value="{{ $aluno->id }}" {{ old('aluno_id') == $aluno->id ? 'selected' : '' }}>
                            {{ $aluno->matricula }} - {{ $aluno->nome }}
                        </option>
                    @endforeach
                </select>
                @error('aluno_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="turma_id" class="form-label">Turma <span class="text-danger">*</span></label>
                <select class="form-select @error('turma_id') is-invalid @enderror" id="turma_id" name="turma_id" required>
                    <option value="">Selecione...</option>
                    @foreach($turmas as $turma)
                        <option value="{{ $turma->id }}" {{ old('turma_id') == $turma->id ? 'selected' : '' }}>
                            {{ $turma->codigo }} - {{ $turma->disciplina->nome ?? 'N/A' }} 
                            ({{ $turma->periodoLetivo->nome_completo ?? 'N/A' }}) 
                            [{{ $turma->vagas_disponiveis }} vagas]
                        </option>
                    @endforeach
                </select>
                @error('turma_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="matriculado" {{ old('status', 'matriculado') == 'matriculado' ? 'selected' : '' }}>Matriculado</option>
                        <option value="trancado" {{ old('status') == 'trancado' ? 'selected' : '' }}>Trancado</option>
                        <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Dependência</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="is_dependencia" 
                               name="is_dependencia" value="1" {{ old('is_dependencia') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_dependencia">
                            Esta matrícula é uma dependência
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Salvar
                </button>
                <a href="{{ route('matriculas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
