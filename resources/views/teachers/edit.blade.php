@extends('layouts.app')

@section('title', 'Editar Professor')

@section('page-title', 'Editar Professor')

@section('page-actions')
<a href="{{ route('teachers.index') }}" class="btn btn-secondary btn-custom">
    <i class="fas fa-arrow-left me-2"></i>Voltar
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('teachers.update', $teacher['id']) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               value="{{ $teacher['name'] }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               value="{{ $teacher['email'] }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="specialization" class="form-label">Especialização</label>
                        <input type="text" class="form-control" id="specialization" name="specialization" required 
                               value="{{ $teacher['specialization'] }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="employment_type" class="form-label">Tipo de Vínculo</label>
                        <select class="form-select" id="employment_type" name="employment_type" required>
                            <option value="integral" {{ $teacher['employment_type'] == 'integral' ? 'selected' : '' }}>Integral</option>
                            <option value="meio periodo" {{ $teacher['employment_type'] == 'meio periodo' ? 'selected' : '' }}>Meio Período</option>
                            <option value="contratado" {{ $teacher['employment_type'] == 'contratado' ? 'selected' : '' }}>Contratado</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Atualizar Professor
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection