@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card text-white" style="background: linear-gradient(45deg, #667eea, #764ba2);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Professores</h5>
                        <h2>{{ count(session('teachers', [])) }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                </div>
                <a href="{{ route('teachers.index') }}" class="btn btn-light btn-sm mt-2">Ver todos</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white" style="background: linear-gradient(45deg, #f093fb, #f5576c);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Estudantes</h5>
                        <h2>{{ count(session('students', [])) }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                </div>
                <a href="{{ route('students.index') }}" class="btn btn-light btn-sm mt-2">Ver todos</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white" style="background: linear-gradient(45deg, #4facfe, #00f2fe);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Disciplinas</h5>
                        <h2>{{ count(session('disciplines', [])) }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
                <a href="{{ route('disciplines.index') }}" class="btn btn-light btn-sm mt-2">Ver todas</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white" style="background: linear-gradient(45deg, #43e97b, #38f9d7);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Salas</h5>
                        <h2>{{ count(session('classrooms', [])) }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-door-open fa-2x"></i>
                    </div>
                </div>
                <a href="{{ route('classrooms.index') }}" class="btn btn-light btn-sm mt-2">Ver todas</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-plus-circle me-2"></i>Ações Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('teachers.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-plus me-2"></i>Novo Professor
                    </a>
                    <a href="{{ route('students.create') }}" class="btn btn-outline-success">
                        <i class="fas fa-user-graduate me-2"></i>Novo Estudante
                    </a>
                    <a href="{{ route('disciplines.create') }}" class="btn btn-outline-info">
                        <i class="fas fa-book-open me-2"></i>Nova Disciplina
                    </a>
                    <a href="{{ route('classrooms.create') }}" class="btn btn-outline-warning">
                        <i class="fas fa-door-open me-2"></i>Nova Sala
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-clock me-2"></i>Informações do Sistema</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-server me-2"></i>Versão Laravel</span>
                        <span class="badge bg-primary rounded-pill">{{ app()->version() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fab fa-php me-2"></i>Versão PHP</span>
                        <span class="badge bg-success rounded-pill">{{ PHP_VERSION }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-database me-2"></i>Modo de Dados</span>
                        <span class="badge bg-warning rounded-pill">Sessão (Temporário)</span>
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
        <div class="alert alert-info">
            <h5><i class="fas fa-info-circle me-2"></i>Informação Importante</h5>
            <p class="mb-0">Este sistema está rodando em <strong>modo demonstração</strong> usando dados armazenados em sessão. 
            Os dados serão perdidos quando a sessão expirar. Para uso em produção, configure um banco de dados MySQL, PostgreSQL ou SQLite.</p>
        </div>
    </div>
</div>
@endsection