@extends('layouts.app')
@section('title', 'Registro de Usuario')

@section('content')
<section style="position:relative; overflow:hidden; border-radius:16px; background: linear-gradient(135deg, #001529 0%, #1890ff 60%, #722ed1 100%); color:white;">
    <div style="position:absolute; inset:0; opacity:.15; background-image: radial-gradient(white 1px, transparent 1px); background-size: 16px 16px;"></div>
    <div style="position:relative; padding:48px 28px; display:flex; justify-content:center;">
        <div class="ant-card" style="width: 100%; max-width: 520px; box-shadow: 0 16px 40px rgba(0,0,0,0.3); border-radius: 12px; overflow:hidden;">
            <div class="ant-card-head">
                <div class="ant-card-head-title" style="display:flex; align-items:center; gap:10px;">
                    <i class="fas fa-user-plus" style="color:#1890ff;"></i>
                    <span style="font-weight:600;">Registro de Usuario</span>
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

            <form id="registroForm">
                <div class="ant-form-item" style="margin-bottom: 12px;">
                    <div class="ant-form-item-label"><label for="nombre">Nombre Completo</label></div>
                    <div class="ant-form-item-control">
                        <span class="ant-input-affix-wrapper" style="width:100%; border:1px solid #d9d9d9; border-radius:6px; padding:0 11px; height:40px; display:flex; align-items:center; background:#fff;">
                            <span class="ant-input-prefix"><i class="fas fa-user" style="color:#8c8c8c;"></i></span>
                            <input class="ant-input" type="text" id="nombre" name="nombre" required placeholder="Tu nombre completo" style="border:none; box-shadow:none; height:38px; flex:1;"/>
                        </span>
                    </div>
                </div>

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

                <div class="ant-alert" style="margin-bottom: 16px; background:#fafafa; border-color:#f0f0f0;">
                    <div class="ant-alert-content">
                        <div class="ant-alert-message" style="font-size:13px; color:#595959;">
                            <i class="fas fa-info-circle" style="margin-right:6px; color:#8c8c8c;"></i>
                            Mínimo 6 caracteres. Se recomienda usar mayúsculas, minúsculas y números.
                        </div>
                    </div>
                </div>

                <div class="ant-form-item">
                    <button type="submit" class="ant-btn ant-btn-primary" style="width:100%; height:40px;">
                        <i class="fas fa-user-plus" style="margin-right:6px;"></i>
                        Crear Cuenta
                    </button>
                </div>
            </form>

            <div style="text-align:center; margin-top: 8px;">
                <span style="color: rgba(0,0,0,0.45);">¿Ya tienes cuenta?</span>
                <a href="{{ route('auth.login') }}" class="ant-btn ant-btn-link" style="padding:0 8px;">Inicia sesión aquí</a>
            </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('registroForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = {
        nombre: formData.get('nombre'),
        correo: formData.get('correo'),
        clave: formData.get('clave')
    };
    try {
        const response = await fetch('/api/registro', {
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
            alert('¡Usuario registrado exitosamente!');
            const expires = new Date(Date.now() + 24*60*60*1000).toUTCString();
            document.cookie = `jwt_token=${result.token}; path=/; expires=${expires}; SameSite=Lax`;
            window.location.href = '/proyectos';
        } else {
            if (result.errors) {
                let errorMessage = 'Errores de validación:\n';
                for (let field in result.errors) {
                    errorMessage += `- ${result.errors[field].join(', ')}\n`;
                }
                alert(errorMessage);
            } else {
                alert('Error: ' + (result.message || 'No fue posible registrar'));
            }
        }
    } catch (error) {
        alert('Error de conexión: ' + error.message);
    }
});
</script>
@endsection
