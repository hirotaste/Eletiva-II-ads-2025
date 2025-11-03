@extends('layouts.app')

@section('title', 'Dashboard - Administrador')

@section('page-title')
    <i class="fas fa-user-shield me-2"></i>Dashboard - Administrador
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-crown me-2"></i>
            <strong>Bem-vindo, {{ auth()->user()->name }}!</strong> Você tem acesso total ao sistema.
        </div>
    </div>
</div>

<div class="row stats-row">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card text-white stats-card" style="background: linear-gradient(45deg, #667eea, #764ba2);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Professores</h5>
                        <h2>{{ $stats['teachers'] }}</h2>
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
        <div class="card text-white stats-card" style="background: linear-gradient(45deg, #f093fb, #f5576c);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Estudantes</h5>
                        <h2>{{ $stats['students'] }}</h2>
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
        <div class="card text-white stats-card" style="background: linear-gradient(45deg, #4facfe, #00f2fe);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Disciplinas</h5>
                        <h2>{{ $stats['disciplines'] }}</h2>
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
        <div class="card text-white stats-card" style="background: linear-gradient(45deg, #43e97b, #38f9d7);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Salas</h5>
                        <h2>{{ $stats['classrooms'] }}</h2>
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

    <!-- Recent Access Logs -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-history me-2"></i>Acessos Recentes</h5>
            </div>
            <div class="card-body">
                @if($recentAccess->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>Ação</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAccess as $log)
                                    <tr>
                                        <td>{{ $log->user ? $log->user->name : 'N/A' }}</td>
                                        <td><small>{{ $log->action }}</small></td>
                                        <td><small>{{ $log->created_at->format('d/m H:i') }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-2"></i>Nenhum acesso registrado ainda.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="alert alert-warning">
            <h5><i class="fas fa-info-circle me-2"></i>Informação Importante</h5>
            <p class="mb-0">Este sistema está rodando em <strong>modo demonstração</strong> usando dados armazenados em sessão. 
            Os dados serão perdidos quando a sessão expirar. Para uso em produção, configure um banco de dados MySQL, PostgreSQL ou SQLite.</p>
        </div>
    </div>
</div>
@endsection
