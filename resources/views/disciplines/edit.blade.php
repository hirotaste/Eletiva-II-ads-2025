@extends('layouts.app')

@section('title', 'Editar Disciplina')

@section('page-title', 'Editar Disciplina')

@section('page-actions')
<a href="{{ route('disciplines.index') }}" class="btn btn-secondary btn-custom">
    <i class="fas fa-arrow-left me-2"></i>Voltar
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('disciplines.update', $discipline['id']) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="code" class="form-label">Código da Disciplina</label>
                        <input type="text" class="form-control" id="code" name="code" required 
                               value="{{ $discipline['code'] }}">
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome da Disciplina</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               value="{{ $discipline['name'] }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="workload_hours" class="form-label">Carga Horária Total</label>
                        <input type="number" class="form-control" id="workload_hours" name="workload_hours" required 
                               min="1" value="{{ $discipline['workload_hours'] }}">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="weekly_hours" class="form-label">Horas Semanais</label>
                        <input type="number" class="form-control" id="weekly_hours" name="weekly_hours" required 
                               min="1" value="{{ $discipline['weekly_hours'] }}">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="credits" class="form-label">Créditos</label>
                        <input type="number" class="form-control" id="credits" name="credits" required 
                               min="1" value="{{ $discipline['credits'] }}">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="obrigatória" {{ $discipline['type'] == 'obrigatória' ? 'selected' : '' }}>Obrigatória</option>
                            <option value="eletiva" {{ $discipline['type'] == 'eletiva' ? 'selected' : '' }}>Eletiva</option>
                            <option value="optativa" {{ $discipline['type'] == 'optativa' ? 'selected' : '' }}>Optativa</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('disciplines.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Atualizar Disciplina
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection