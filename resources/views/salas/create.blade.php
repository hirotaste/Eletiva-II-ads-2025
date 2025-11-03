@extends('layouts.app')

@section('title', 'Nova Sala')

@section('page-title', 'Cadastrar Nova Sala')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('salas.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="codigo" class="form-label">Código <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                           id="codigo" name="codigo" value="{{ old('codigo') }}" required>
                    @error('codigo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-8 mb-3">
                    <label for="nome" class="form-label">Nome da Sala <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                           id="nome" name="nome" value="{{ old('nome') }}" required>
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="capacidade" class="form-label">Capacidade <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('capacidade') is-invalid @enderror" 
                           id="capacidade" name="capacidade" value="{{ old('capacidade') }}" 
                           min="1" required>
                    @error('capacidade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo">
                        <option value="">Selecione...</option>
                        <option value="sala_aula" {{ old('tipo') == 'sala_aula' ? 'selected' : '' }}>Sala de Aula</option>
                        <option value="laboratorio" {{ old('tipo') == 'laboratorio' ? 'selected' : '' }}>Laboratório</option>
                        <option value="auditorio" {{ old('tipo') == 'auditorio' ? 'selected' : '' }}>Auditório</option>
                    </select>
                    @error('tipo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Recursos Disponíveis</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="possui_projetor" 
                           name="possui_projetor" value="1" {{ old('possui_projetor') ? 'checked' : '' }}>
                    <label class="form-check-label" for="possui_projetor">
                        Projetor
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="possui_ar_condicionado" 
                           name="possui_ar_condicionado" value="1" {{ old('possui_ar_condicionado') ? 'checked' : '' }}>
                    <label class="form-check-label" for="possui_ar_condicionado">
                        Ar Condicionado
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="possui_computadores" 
                           name="possui_computadores" value="1" {{ old('possui_computadores') ? 'checked' : '' }}>
                    <label class="form-check-label" for="possui_computadores">
                        Computadores
                    </label>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Salvar
                </button>
                <a href="{{ route('salas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
