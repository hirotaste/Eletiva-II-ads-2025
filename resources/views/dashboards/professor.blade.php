@extends('layouts.app')

@section('title', 'Dashboard - Professor')

@section('page-title')
    <i class="fas fa-chalkboard-teacher me-2"></i>Dashboard - Professor
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-chalkboard-teacher me-2"></i>
            <strong>Bem-vindo, {{ auth()->user()->name }}!</strong> Aqui você pode gerenciar suas disciplinas e alunos.
        </div>
    </div>
</div>

<div class="row stats-row">
    <!-- Statistics Cards -->
    <div class="col-md-6 mb-4">
        <div class="card text-white stats-card" style="background: linear-gradient(45deg, #4facfe, #00f2fe);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Disciplinas</h5>
                        <h2>{{ $stats['disciplines'] }}</h2>
                        <p class="mb-0"><small>Disciplinas disponíveis</small></p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-3x"></i>
                    </div>
                </div>
                <a href="{{ route('disciplines.index') }}" class="btn btn-light btn-sm mt-2">Ver disciplinas</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card text-white stats-card" style="background: linear-gradient(45deg, #f093fb, #f5576c);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Estudantes</h5>
                        <h2>{{ $stats['students'] }}</h2>
                        <p class="mb-0"><small>Total de estudantes</small></p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-graduate fa-3x"></i>
                    </div>
                </div>
                <a href="{{ route('students.index') }}" class="btn btn-light btn-sm mt-2">Ver estudantes</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-tasks me-2"></i>Ações Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('disciplines.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-book me-2"></i>Minhas Disciplinas
                    </a>
                    <a href="{{ route('students.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-users me-2"></i>Lista de Estudantes
                    </a>
                    <a href="{{ route('classrooms.index') }}" class="btn btn-outline-info">
                        <i class="fas fa-door-open me-2"></i>Salas de Aula
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- My Info -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-user me-2"></i>Minhas Informações</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-envelope me-2"></i>E-mail</span>
                        <span class="badge bg-primary rounded-pill">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-id-badge me-2"></i>Nível</span>
                        <span class="badge bg-success rounded-pill">Professor</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-calendar me-2"></i>Data Atual</span>
                        <span class="badge bg-info rounded-pill">{{ date('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Recursos do Professor</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar e gerenciar disciplinas</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Acessar lista de estudantes</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar salas de aula</li>
                    <li><i class="fas fa-check text-success me-2"></i>Consultar informações acadêmicas</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
