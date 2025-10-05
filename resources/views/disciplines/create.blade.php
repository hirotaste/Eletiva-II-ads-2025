@extends('layouts.app')

@section('title', 'Nova Disciplina')

@section('page-title', 'Cadastrar Nova Disciplina')

@section('page-actions')
<a href="{{ route('disciplines.index') }}" class="btn btn-secondary btn-custom">
    <i class="fas fa-arrow-left me-2"></i>Voltar
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('disciplines.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="code" class="form-label">Código da Disciplina</label>
                        <input type="text" class="form-control" id="code" name="code" required 
                               placeholder="Ex: ENG001">
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome da Disciplina</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               placeholder="Ex: Engenharia de Software I">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="workload_hours" class="form-label">Carga Horária Total</label>
                        <input type="number" class="form-control" id="workload_hours" name="workload_hours" required 
                               min="1" placeholder="80">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="weekly_hours" class="form-label">Horas Semanais</label>
                        <input type="number" class="form-control" id="weekly_hours" name="weekly_hours" required 
                               min="1" placeholder="4">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="credits" class="form-label">Créditos</label>
                        <input type="number" class="form-control" id="credits" name="credits" required 
                               min="1" placeholder="4">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Selecione...</option>
                            <option value="obrigatória">Obrigatória</option>
                            <option value="eletiva">Eletiva</option>
                            <option value="optativa">Optativa</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('disciplines.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Salvar Disciplina
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
            <li><i class="fas fa-check text-success me-2"></i>O código deve ser único no sistema</li>
            <li><i class="fas fa-check text-success me-2"></i>Carga horária deve ser maior que zero</li>
        </ul>
    </div>
</div>
@endsection