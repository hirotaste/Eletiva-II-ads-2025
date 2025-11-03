@extends('layouts.app')

@section('title', 'Períodos Letivos')

@section('page-title', 'Gerenciar Períodos Letivos')

@section('page-actions')
<a href="{{ route('periodos-letivos.create') }}" class="btn btn-primary btn-custom">
    <i class="fas fa-plus me-2"></i>Novo Período Letivo
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if($periodos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Ano/Semestre</th>
                            <th>Data Início</th>
                            <th>Data Fim</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($periodos as $periodo)
                        <tr>
                            <td><strong>{{ $periodo->nome_completo }}</strong></td>
                            <td>{{ $periodo->data_inicio->format('d/m/Y') }}</td>
                            <td>{{ $periodo->data_fim->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'planejamento' => 'warning',
                                        'ativo' => 'success',
                                        'finalizado' => 'secondary'
                                    ];
                                    $color = $statusColors[$periodo->status] ?? 'info';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ ucfirst($periodo->status ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="action-buttons-container">
                                    <a href="{{ route('periodos-letivos.edit', $periodo->id) }}" class="btn btn-outline-primary action-btn" title="Editar Período">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('periodos-letivos.destroy', $periodo->id) }}" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir este período letivo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger action-btn" title="Excluir Período">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhum período letivo cadastrado</h5>
                <p class="text-muted">Clique no botão "Novo Período Letivo" para adicionar o primeiro período.</p>
                <a href="{{ route('periodos-letivos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cadastrar Primeiro Período
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
