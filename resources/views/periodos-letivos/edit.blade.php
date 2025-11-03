@extends('layouts.app')

@section('title', 'Editar Período Letivo')

@section('page-title', 'Editar Período Letivo')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('periodos-letivos.update', $periodo->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="ano" class="form-label">Ano <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('ano') is-invalid @enderror" 
                           id="ano" name="ano" value="{{ old('ano', $periodo->ano) }}" 
                           min="2000" max="2100" required>
                    @error('ano')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="semestre" class="form-label">Semestre <span class="text-danger">*</span></label>
                    <select class="form-select @error('semestre') is-invalid @enderror" id="semestre" name="semestre" required>
                        <option value="">Selecione...</option>
                        <option value="1" {{ old('semestre', $periodo->semestre) == '1' ? 'selected' : '' }}>1º Semestre</option>
                        <option value="2" {{ old('semestre', $periodo->semestre) == '2' ? 'selected' : '' }}>2º Semestre</option>
                    </select>
                    @error('semestre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="data_inicio" class="form-label">Data de Início <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" 
                           id="data_inicio" name="data_inicio" 
                           value="{{ old('data_inicio', $periodo->data_inicio->format('Y-m-d')) }}" required>
                    @error('data_inicio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="data_fim" class="form-label">Data de Fim <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('data_fim') is-invalid @enderror" 
                           id="data_fim" name="data_fim" 
                           value="{{ old('data_fim', $periodo->data_fim->format('Y-m-d')) }}" required>
                    @error('data_fim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                    <option value="">Selecione...</option>
                    <option value="planejamento" {{ old('status', $periodo->status) == 'planejamento' ? 'selected' : '' }}>Planejamento</option>
                    <option value="ativo" {{ old('status', $periodo->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="finalizado" {{ old('status', $periodo->status) == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Atualizar
                </button>
                <a href="{{ route('periodos-letivos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
