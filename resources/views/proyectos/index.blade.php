@extends('layouts.app')

@section('title', 'Lista de Proyectos')

@section('content')
@php $hasJwt = request()->cookie('jwt_token') ?? ($_COOKIE['jwt_token'] ?? null); @endphp
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; margin-bottom: 24px;">
    <div>
        @include('components.uf-display', ['showRefresh' => true])
    </div>
    <div class="ant-card">
        <div class="ant-card-body" style="text-align: center;">
            @if(!$hasJwt)
                <div class="ant-alert ant-alert-warning" role="alert" style="margin-bottom: 16px; text-align:left;">
                    <span class="ant-alert-icon"><i class="fas fa-exclamation-circle"></i></span>
                    <div class="ant-alert-content">
                        <div class="ant-alert-message">Estás viendo en modo lectura</div>
                        <div class="ant-alert-description">Inicia sesión para crear, editar o eliminar proyectos.</div>
                    </div>
                </div>
            @endif
            <h5 style="margin-bottom: 16px; color: rgba(0, 0, 0, 0.85);">
                <i class="fas fa-chart-bar" style="margin-right: 8px;"></i>
                Resumen de Proyectos
            </h5>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
                <div class="ant-statistic">
                    <div class="ant-statistic-title">Total Proyectos</div>
                    <div class="ant-statistic-content" style="color: var(--ant-primary-color);">
                        {{ $proyectos->count() }}
                    </div>
                </div>
                <div class="ant-statistic">
                    <div class="ant-statistic-title">Completados</div>
                    <div class="ant-statistic-content" style="color: var(--ant-success-color);">
                        {{ $proyectos->where('estado', 'Completado')->count() }}
                    </div>
                </div>
                <div class="ant-statistic">
                    <div class="ant-statistic-title">En Progreso</div>
                    <div class="ant-statistic-content" style="color: var(--ant-warning-color);">
                        {{ $proyectos->where('estado', 'En Progreso')->count() }}
                    </div>
                </div>
                <div class="ant-statistic">
                    <div class="ant-statistic-title">Total Invertido</div>
                    <div class="ant-statistic-content" style="color: #722ed1;">
                        ${{ number_format($proyectos->sum('monto'), 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
        <div class="ant-card">
            <div class="ant-card-head">
                <div class="ant-card-head-wrapper">
                    <div class="ant-card-head-title" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                        <span>
                            <i class="fas fa-list" style="margin-right: 8px;"></i>
                            Lista de Proyectos
                        </span>
                        @if($hasJwt)
                            <a href="{{ route('proyectos.create') }}" class="ant-btn ant-btn-primary" style="display:inline-flex; align-items:center; gap:8px;">
                                <i class="fas fa-plus"></i>
                                Crear Proyecto
                            </a>
                        @endif

                    </div>
                </div>
            </div>
            <div class="ant-card-body">

                
                @if($proyectos->count() > 0)
                    <div class="ant-table-wrapper" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
                        <div class="ant-table">
                            <div class="ant-table-container">
                                <div class="ant-table-content">
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <thead class="ant-table-thead">
                                            <tr>
                                                <th class="ant-table-cell" style="background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%); padding: 20px 16px; text-align: left; font-weight: 600; border-bottom: 2px solid #e8e8e8; color: rgba(0, 0, 0, 0.85);">ID</th>
                                                <th class="ant-table-cell" style="background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%); padding: 20px 16px; text-align: left; font-weight: 600; border-bottom: 2px solid #e8e8e8; color: rgba(0, 0, 0, 0.85);">Nombre</th>
                                                <th class="ant-table-cell" style="background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%); padding: 20px 16px; text-align: left; font-weight: 600; border-bottom: 2px solid #e8e8e8; color: rgba(0, 0, 0, 0.85);">Fecha de Inicio</th>
                                                <th class="ant-table-cell" style="background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%); padding: 20px 16px; text-align: left; font-weight: 600; border-bottom: 2px solid #e8e8e8; color: rgba(0, 0, 0, 0.85);">Estado</th>
                                                <th class="ant-table-cell" style="background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%); padding: 20px 16px; text-align: left; font-weight: 600; border-bottom: 2px solid #e8e8e8; color: rgba(0, 0, 0, 0.85);">Responsable</th>
                                                <th class="ant-table-cell" style="background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%); padding: 20px 16px; text-align: left; font-weight: 600; border-bottom: 2px solid #e8e8e8; color: rgba(0, 0, 0, 0.85);">Monto</th>
                                                <th class="ant-table-cell" style="background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%); padding: 20px 16px; text-align: left; font-weight: 600; border-bottom: 2px solid #e8e8e8; color: rgba(0, 0, 0, 0.85);">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="ant-table-tbody">
                                            @foreach($proyectos as $index => $proyecto)
                                                <tr class="ant-table-row" style="transition: all 0.3s ease; animation: fadeInUp 0.5s ease {{ $index * 0.1 }}s both;">
                                                    <td class="ant-table-cell" style="padding: 20px 16px; border-bottom: 1px solid #f0f0f0; background: white;">{{ $proyecto->id }}</td>
                                                    <td class="ant-table-cell" style="padding: 20px 16px; border-bottom: 1px solid #f0f0f0; background: white;">
                                                        <strong style="color: rgba(0, 0, 0, 0.85);">{{ $proyecto->nombre }}</strong>
                                                    </td>
                                                    <td class="ant-table-cell" style="padding: 20px 16px; border-bottom: 1px solid #f0f0f0; background: white; color: rgba(0, 0, 0, 0.65);">{{ $proyecto->fecha_inicio->format('d/m/Y') }}</td>
                                                    <td class="ant-table-cell" style="padding: 20px 16px; border-bottom: 1px solid #f0f0f0; background: white;">
                                                        @php
                                                            $estadoClass = match($proyecto->estado) {
                                                                'En Progreso' => 'ant-badge ant-badge-status ant-badge-status-processing',
                                                                'Completado' => 'ant-badge ant-badge-status ant-badge-status-success',
                                                                'Pendiente' => 'ant-badge ant-badge-status ant-badge-status-default',
                                                                'Cancelado' => 'ant-badge ant-badge-status ant-badge-status-error',
                                                                default => 'ant-badge ant-badge-status ant-badge-status-warning'
                                                            };
                                                        @endphp
                                                        <span class="{{ $estadoClass }}" style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                                            <span class="ant-badge-status-dot" style="margin-right: 6px;"></span>
                                                            {{ $proyecto->estado }}
                                                        </span>
                                                    </td>
                                                    <td class="ant-table-cell" style="padding: 20px 16px; border-bottom: 1px solid #f0f0f0; background: white; color: rgba(0, 0, 0, 0.65);">{{ $proyecto->responsable }}</td>
                                                    <td class="ant-table-cell" style="padding: 20px 16px; border-bottom: 1px solid #f0f0f0; background: white; font-weight: 600; color: var(--ant-success-color); font-size: 15px;">
                                                        ${{ number_format($proyecto->monto, 0, ',', '.') }}
                                                    </td>
                                                    <td class="ant-table-cell" style="padding: 20px 16px; border-bottom: 1px solid #f0f0f0; background: white;">
                                                        <div style="display: flex; gap: 8px;">
                                                            <a href="{{ route('proyectos.show', $proyecto->id) }}" 
                                                               class="ant-btn ant-btn-text" 
                                                               title="Ver detalles"
                                                               style="padding: 8px 12px; min-width: auto; border-radius: 8px; transition: all 0.3s ease;">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if($hasJwt)
                                                                <a href="{{ route('proyectos.edit', $proyecto->id) }}" 
                                                                   class="ant-btn ant-btn-text" 
                                                                   title="Editar"
                                                                   style="padding: 8px 12px; min-width: auto; border-radius: 8px; transition: all 0.3s ease;">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('proyectos.destroy', $proyecto->id) }}" 
                                                                      method="POST" 
                                                                      style="display: inline;"
                                                                      onsubmit="return confirm('¿Estás seguro de que quieres eliminar este proyecto?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" 
                                                                            class="ant-btn ant-btn-text ant-btn-dangerous" 
                                                                            title="Eliminar"
                                                                            style="padding: 8px 12px; min-width: auto; border-radius: 8px; transition: all 0.3s ease;">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div style="text-align: center; padding: 48px 0;">
                        <i class="fas fa-folder-open" style="font-size: 48px; color: rgba(0, 0, 0, 0.25); margin-bottom: 16px;"></i>
                        <h5 style="color: rgba(0, 0, 0, 0.45); margin-bottom: 8px;">No hay proyectos registrados</h5>
                        <p style="color: rgba(0, 0, 0, 0.45); margin-bottom: 16px;">Comienza creando tu primer proyecto</p>
                        <a href="{{ route('proyectos.create') }}" class="ant-btn ant-btn-primary">
                            <i class="fas fa-plus"></i>
                            Crear Proyecto
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 