<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - Sistema de Proyectos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .registro-container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .logo {
            font-size: 2.5em;
            color: #667eea;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 1.8em;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .links {
            margin-top: 20px;
        }

        .links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .error {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fcc;
        }

        .success {
            background: #efe;
            color: #363;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #cfc;
        }

        .password-requirements {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: left;
        }

        .password-requirements h4 {
            color: #495057;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .password-requirements ul {
            color: #6c757d;
            font-size: 12px;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="registro-container">
        <div class="logo">
            <i class="fas fa-user-plus"></i>
        </div>
        
        <h1>Registro de Usuario</h1>

        @if(session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        <form id="registroForm">
            <div class="form-group">
                <label for="nombre">
                    <i class="fas fa-user"></i> Nombre Completo
                </label>
                <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre completo">
            </div>

            <div class="form-group">
                <label for="correo">
                    <i class="fas fa-envelope"></i> Correo Electrónico
                </label>
                <input type="email" id="correo" name="correo" required placeholder="tu@email.com">
            </div>

            <div class="form-group">
                <label for="clave">
                    <i class="fas fa-lock"></i> Contraseña
                </label>
                <input type="password" id="clave" name="clave" required placeholder="Tu contraseña">
            </div>

            <div class="password-requirements">
                <h4><i class="fas fa-info-circle"></i> Requisitos de la contraseña:</h4>
                <ul>
                    <li>Mínimo 6 caracteres</li>
                    <li>Se recomienda usar mayúsculas, minúsculas y números</li>
                </ul>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-user-plus"></i> Crear Cuenta
            </button>
        </form>

        <div class="links">
            <p>¿Ya tienes cuenta? <a href="{{ route('auth.login') }}">Inicia sesión aquí</a></p>
        </div>
    </div>

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
                    // Guardar token en localStorage
                    localStorage.setItem('jwt_token', result.token);
                    localStorage.setItem('user_info', JSON.stringify(result.user));
                    
                    alert('¡Usuario registrado exitosamente!');
                    
                    // Redirigir a proyectos
                    window.location.href = '/proyectos';
                } else {
                    if (result.errors) {
                        let errorMessage = 'Errores de validación:\n';
                        for (let field in result.errors) {
                            errorMessage += `- ${result.errors[field].join(', ')}\n`;
                        }
                        alert(errorMessage);
                    } else {
                        alert('Error: ' + result.message);
                    }
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        });
    </script>
</body>
</html>
