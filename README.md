# EV2 – Sistema de Gestión de Proyectos (Laravel 12)

Aplicación web para la Evaluación 2 de “Desarrollo de Software Web I”. Construida con Laravel 12 (PHP 8.2.29) y MySQL. Continúa EV1 e incorpora autenticación JWT y CRUD protegido de Proyectos.

---

## Requisitos
- PHP 8.2.29
- Composer
- MySQL en 127.0.0.1:3306

## Instalación rápida
1) Clonar repo y entrar a la carpeta
2) Instalar dependencias: `composer install`
3) Crear `.env` (o copiar desde `.env.example` si existe) y ajustar credenciales MySQL
4) Migraciones: `php artisan migrate`
5) Levantar: `php artisan serve` y abrir http://127.0.0.1:8000

Autenticación: regístrate en `/registro` y luego inicia sesión en `/login`. El CRUD de Proyectos requiere estar logueado.

## Estructura del Proyecto

```
EV2/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php        # Registro/Login + JWT
│   │   │   ├── HomeController.php        # Home público
│   │   │   └── ProyectoController.php    # CRUD de Proyectos
│   │   └── Middleware/
│   │       └── JWTAuthMiddleware.php     # Protege rutas con JWT (cookie o Bearer)
│   ├── Models/
│   │   ├── Proyecto.php                  # Modelo proyecto
│   │   └── User.php                      # Modelo usuario (hash de clave)
│   └── Services/
│       └── UFService.php                 # Servicio para mostrar UF
├── bootstrap/
│   └── app.php                           # Bootstrap Laravel + alias middleware
├── config/                               # Configuración (app, database, etc.)
├── database/
│   ├── migrations/                       # Tablas users, cache, jobs, proyectos
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   └── 2025_07_22_150610_create_proyectos_table.php
│   └── seeders/                          # (presentes, no usados en EV2)
├── public/
│   ├── index.php                         # Front controller
│   └── logo.png                          # Logo
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php             # Layout principal
│   │   ├── auth/
│   │   │   ├── login.blade.php           # Iniciar sesión
│   │   │   └── registro.blade.php        # Registro
│   │   ├── proyectos/
│   │   │   ├── index.blade.php           # Listado
│   │   │   ├── create.blade.php          # Crear
│   │   │   ├── edit.blade.php            # Editar
│   │   │   └── show.blade.php            # Detalle
│   │   ├── components/
│   │   │   └── uf-display.blade.php      # Widget UF
│   │   └── home.blade.php                # Portada
│   └── js/bootstrap.js                   # JS base
├── routes/
│   ├── api.php                           # Registro/Login API y CRUD API protegido
│   └── web.php                           # Rutas web (públicas y protegidas)
├── .env                                  # Configuración local (APP_KEY, DB, JWT)
├── composer.json                         # Dependencias
└── README.md
```

## Uso rápido
- Ir a `/registro` para crear usuario y luego a `/login`.
- Sección Proyectos en `/proyectos`: crear, ver, editar y eliminar (requiere sesión).

## Notas
- No se usan seeders en EV2.
- El token JWT se guarda en cookie `jwt_token` (SameSite=Lax) y también se acepta Bearer.

## Autor
José Jara Canales – EV2, Desarrollo de Software Web I
