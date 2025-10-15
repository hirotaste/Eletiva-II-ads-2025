@extends('layouts.app')

@section('title', 'Dashboard - Estudante')

@section('page-title')
    <i class="fas fa-user-graduate me-2"></i>Dashboard - Estudante
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-user-graduate me-2"></i>
            <strong>Bem-vindo, {{ auth()->user()->name }}!</strong> Aqui você pode acessar suas informações acadêmicas.
        </div>
    </div>
</div>

<div class="row stats-row">
    <!-- Statistics Cards -->
    <div class="col-md-12 mb-4">
        <div class="card text-white stats-card" style="background: linear-gradient(45deg, #4facfe, #00f2fe);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Disciplinas Disponíveis</h5>
                        <h2>{{ $stats['disciplines'] }}</h2>
                        <p class="mb-0"><small>Disciplinas no sistema</small></p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book-open fa-3x"></i>
                    </div>
                </div>
                <a href="{{ route('disciplines.index') }}" class="btn btn-light btn-sm mt-2">Ver disciplinas</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-tasks me-2"></i>Acesso Rápido</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('disciplines.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-book me-2"></i>Ver Disciplinas
                    </a>
                    <a href="{{ route('teachers.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Ver Professores
                    </a>
                    <a href="{{ route('classrooms.index') }}" class="btn btn-outline-info">
                        <i class="fas fa-door-open me-2"></i>Ver Salas
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
                        <span><i class="fas fa-user me-2"></i>Nome</span>
                        <span class="badge bg-primary rounded-pill">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-envelope me-2"></i>E-mail</span>
                        <span class="badge bg-info rounded-pill">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-id-badge me-2"></i>Nível</span>
                        <span class="badge bg-success rounded-pill">Estudante</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-calendar me-2"></i>Data</span>
                        <span class="badge bg-secondary rounded-pill">{{ date('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Área do Estudante</h5>
            </div>
            <div class="card-body">
                <p>Como estudante, você tem acesso a:</p>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Visualizar disciplinas disponíveis</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Consultar professores</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Ver informações sobre salas de aula</li>
                    <li><i class="fas fa-check text-success me-2"></i>Acompanhar seu progresso acadêmico</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-light border">
            <h5><i class="fas fa-lightbulb me-2"></i>Dica</h5>
            <p class="mb-0">Mantenha suas informações atualizadas e acesse regularmente o sistema para acompanhar novidades e atualizações.</p>
        </div>
    </div>
</div>
@endsection
