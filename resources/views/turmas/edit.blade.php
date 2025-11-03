@extends('layouts.app')

@section('title', 'Editar Turma')

@section('page-title', 'Editar Turma')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('turmas.update', $turma->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="periodo_letivo_id" class="form-label">Período Letivo <span class="text-danger">*</span></label>
                    <select class="form-select @error('periodo_letivo_id') is-invalid @enderror" id="periodo_letivo_id" name="periodo_letivo_id" required>
                        <option value="">Selecione...</option>
                        @foreach($periodos as $periodo)
                            <option value="{{ $periodo->id }}" {{ old('periodo_letivo_id', $turma->periodo_letivo_id) == $periodo->id ? 'selected' : '' }}>
                                {{ $periodo->nome_completo }} ({{ $periodo->status }})
                            </option>
                        @endforeach
                    </select>
                    @error('periodo_letivo_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="codigo" class="form-label">Código da Turma <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                           id="codigo" name="codigo" value="{{ old('codigo', $turma->codigo) }}" required>
                    @error('codigo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="disciplina_id" class="form-label">Disciplina</label>
                    <select class="form-select @error('disciplina_id') is-invalid @enderror" id="disciplina_id" name="disciplina_id">
                        <option value="">Selecione...</option>
                        @foreach($disciplinas as $disciplina)
                            <option value="{{ $disciplina->id }}" {{ old('disciplina_id', $turma->disciplina_id) == $disciplina->id ? 'selected' : '' }}>
                                {{ $disciplina->codigo }} - {{ $disciplina->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('disciplina_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="professor_id" class="form-label">Professor</label>
                    <select class="form-select @error('professor_id') is-invalid @enderror" id="professor_id" name="professor_id">
                        <option value="">Selecione...</option>
                        @foreach($professores as $professor)
                            <option value="{{ $professor->id }}" {{ old('professor_id', $turma->professor_id) == $professor->id ? 'selected' : '' }}>
                                {{ $professor->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('professor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="vagas_total" class="form-label">Vagas Totais <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('vagas_total') is-invalid @enderror" 
                           id="vagas_total" name="vagas_total" value="{{ old('vagas_total', $turma->vagas_total) }}" 
                           min="{{ $turma->vagas_ocupadas }}" required>
                    @error('vagas_total')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Mínimo: {{ $turma->vagas_ocupadas }} (vagas ocupadas)</small>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Atualizar
                </button>
                <a href="{{ route('turmas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
