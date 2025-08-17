@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
@php $hasJwt = request()->cookie('jwt_token'); @endphp

<div style="display:flex; flex-direction:column; gap:24px;">
    @isset($db_error)
        <div class="ant-alert ant-alert-error" role="alert" style="padding:12px; border:1px solid #ffccc7; background:#fff2f0; border-radius:8px;">
            <i class="fas fa-database" style="margin-right:8px; color:#cf1322;"></i>
            {{ $db_error }}
        </div>
    @endisset

    <!-- Hero Section -->
    <section style="position:relative; overflow:hidden; border-radius:16px; background: linear-gradient(135deg, #001529 0%, #1890ff 60%, #722ed1 100%); color:white;">
        <div style="position:absolute; inset:0; opacity:.15; background-image: radial-gradient(white 1px, transparent 1px); background-size: 16px 16px;"></div>
        <div style="position:relative; padding:40px 28px; display:grid; grid-template-columns: 1.25fr .75fr; gap:28px; align-items:center;">
            <div style="animation: slideInLeft .6s ease both;">
                <h1 style="margin:0 0 12px 0; font-size:32px; line-height:1.2; font-weight:800;">
                    Bienvenido al sistema de gestión de proyectos
                </h1>
                <p style="margin:0 0 20px 0; font-size:16px; opacity:.9; max-width:720px;">
                    Inicia sesión para poder gestionar tus proyectos de manera eficiente.
                </p>
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    @if(!$hasJwt)
                        <a href="{{ route('auth.login') }}" class="ant-btn" style="height:44px; padding: 0 20px; font-weight:600; background: rgba(255,255,255,.15); color:white; border-color:rgba(255,255,255,.35);">
                            <i class="fas fa-sign-in-alt" style="margin-right:8px;"></i>
                            Iniciar sesión
                        </a>
                        <a href="{{ route('auth.registro') }}" class="ant-btn" style="height:44px; padding: 0 20px; font-weight:600; background: rgba(255,255,255,.15); color:white; border-color:rgba(255,255,255,.35);">
                            <i class="fas fa-user-plus" style="margin-right:8px;"></i>
                            Registrarse
                        </a>
                    @endif
                </div>
            </div>
            <div style="animation: fadeInUp .7s ease .1s both;">
                @include('components.uf-display', ['showRefresh' => true])
            </div>
        </div>
    </section>

    <!-- Features -->
    <section style="display:grid; grid-template-columns: repeat(2, 1fr); gap:16px;">
        <div class="ant-card" style="animation: fadeInUp .5s ease .1s both;">
            <div class="ant-card-body" style="display:flex; gap:12px; align-items:flex-start;">
                <div style="width:40px; height:40px; border-radius:8px; background: #f6ffed; display:flex; align-items:center; justify-content:center; color:#52c41a;">
                    <i class="fas fa-lock"></i>
                </div>
                <div>
                    <div style="font-weight:700; color: rgba(0,0,0,.85);">Acciones protegidas</div>
                    <div style="color: rgba(0,0,0,.6); font-size:13px;">Crear, editar y eliminar requieren autenticación JWT.</div>
                </div>
            </div>
        </div>
        <div class="ant-card" style="animation: fadeInUp .5s ease .05s both;">
            <div class="ant-card-body" style="display:flex; gap:12px; align-items:flex-start;">
                <div style="width:40px; height:40px; border-radius:8px; background: #fff7e6; display:flex; align-items:center; justify-content:center; color:#fa8c16;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <div style="font-weight:700; color: rgba(0,0,0,.85);">Datos en contexto</div>
                    <div style="color: rgba(0,0,0,.6); font-size:13px;">Valor UF en tiempo real para decisiones informadas.</div>
                </div>
            </div>
        </div>
    </section>

    
</div>
@endsection
