@extends('layouts.app')
@section('title', 'Iniciar Sesión')

@section('content')
<section style="position:relative; overflow:hidden; border-radius:16px; background: linear-gradient(135deg, #001529 0%, #1890ff 60%, #722ed1 100%); color:white;">
    <div style="position:absolute; inset:0; opacity:.15; background-image: radial-gradient(white 1px, transparent 1px); background-size: 16px 16px;"></div>
    <div style="position:relative; padding:48px 28px; display:flex; justify-content:center;">
        <div class="ant-card" style="width: 100%; max-width: 500px; box-shadow: 0 16px 40px rgba(0,0,0,0.3); border-radius: 12px; overflow:hidden;">
            <div class="ant-card-head">
                <div class="ant-card-head-title" style="display:flex; align-items:center; gap:10px;">
                    <i class="fas fa-sign-in-alt" style="color:#1890ff;"></i>
                    <span style="font-weight:600;">Iniciar Sesión</span>
                </div>
            </div>
            <div class="ant-card-body">
                <div style="display:flex; justify-content:center; margin-bottom: 12px;">
                    <img src="{{ asset('logo.png') }}" alt="Logo" style="height:42px; width:auto;"/>
                </div>
            @if(session('error'))
                <div class="ant-alert ant-alert-error" role="alert" style="margin-bottom: 16px;">
                    <span class="ant-alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="ant-alert-content">
                        <div class="ant-alert-message">{{ session('error') }}</div>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="ant-alert ant-alert-success" role="alert" style="margin-bottom: 16px;">
                    <span class="ant-alert-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="ant-alert-content">
                        <div class="ant-alert-message">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            <form id="loginForm">
                <div class="ant-form-item" style="margin-bottom: 12px;">
                    <div class="ant-form-item-label"><label for="correo">Correo Electrónico</label></div>
                    <div class="ant-form-item-control">
                        <span class="ant-input-affix-wrapper" style="width:100%; border:1px solid #d9d9d9; border-radius:6px; padding:0 11px; height:40px; display:flex; align-items:center; background:#fff;">
                            <span class="ant-input-prefix"><i class="fas fa-envelope" style="color:#8c8c8c;"></i></span>
                            <input class="ant-input" type="email" id="correo" name="correo" required placeholder="tu@email.com" style="border:none; box-shadow:none; height:38px; flex:1;"/>
                        </span>
                    </div>
                </div>

                <div class="ant-form-item" style="margin-bottom: 16px;">
                    <div class="ant-form-item-label"><label for="clave">Contraseña</label></div>
                    <div class="ant-form-item-control">
                        <span class="ant-input-affix-wrapper" style="width:100%; border:1px solid #d9d9d9; border-radius:6px; padding:0 11px; height:40px; display:flex; align-items:center; background:#fff;">
                            <span class="ant-input-prefix"><i class="fas fa-lock" style="color:#8c8c8c;"></i></span>
                            <input class="ant-input" type="password" id="clave" name="clave" required placeholder="Tu contraseña" style="border:none; box-shadow:none; height:38px; flex:1;"/>
                        </span>
                    </div>
                </div>

                <div class="ant-form-item">
                    <button type="submit" class="ant-btn ant-btn-primary" style="width:100%; height:40px;">
                        <i class="fas fa-sign-in-alt" style="margin-right:6px;"></i>
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            <div style="text-align:center; margin-top: 8px;">
                <span style="color: rgba(0,0,0,0.45);">¿No tienes cuenta?</span>
                <a href="{{ route('auth.registro') }}" class="ant-btn ant-btn-link" style="padding:0 8px;">Regístrate aquí</a>
            </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = {
        correo: formData.get('correo'),
        clave: formData.get('clave')
    };

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            localStorage.setItem('jwt_token', result.token);
            localStorage.setItem('user_info', JSON.stringify(result.user));
            const expires = new Date(Date.now() + 24*60*60*1000).toUTCString();
            document.cookie = `jwt_token=${result.token}; path=/; expires=${expires}; SameSite=Lax`;
            window.location.href = '/proyectos';
        } else {
            alert('Error: ' + (result.message || 'No fue posible iniciar sesión'));
        }
    } catch (error) {
        alert('Error de conexión: ' + error.message);
    }
});
</script>
@endsection
