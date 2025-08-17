# Sistema de Gestión de Proyectos – Guía de Evaluación (Unidad 2)

Este documento complementa el README principal y está en español para facilitar la evaluación. Explica el proyecto, cómo ejecutarlo sin errores, los endpoints, el flujo JWT, el módulo UF y un checklist de rúbrica con referencias a archivos.

## 1) Objetivo y alcance
- Laravel 12 (PHP 8.2) para CRUD de proyectos.
- Autenticación JWT (firebase/php-jwt) con middleware propio.
- Integración de valor UF en tiempo real (Mindicador + respaldo Santa.cl).
- Vistas Blade (SSR) y API REST.

## 2) Requisitos previos
- PHP 8.2+, Composer.
- MySQL en 127.0.0.1 puerto 3307 (pdo_mysql habilitado).

## 3) Configuración del entorno (.env)
Ajusta credenciales (puerto 3307 ya configurado):

```
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=desarrollo_software_1
DB_USERNAME=TU_USUARIO
DB_PASSWORD=TU_PASSWORD

JWT_SECRET=clave_super_secreta_para_jwt_2025
```

Si necesitas crear un usuario de app y la BD:

```sql
CREATE DATABASE IF NOT EXISTS desarrollo_software_1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'app_user'@'%' IDENTIFIED BY 'app_password_123';
GRANT ALL PRIVILEGES ON desarrollo_software_1.* TO 'app_user'@'%';
FLUSH PRIVILEGES;
```

## 4) Instalación y puesta en marcha

```bash
composer install
php artisan key:generate
php artisan config:clear
php artisan config:cache
php artisan migrate --force
php artisan serve
```

Nota: Esta evaluación no requiere seeders. Las tablas se crean con migraciones. Puedes insertar datos manualmente por UI o SQL si lo deseas.

## 5) Rutas y controladores
- Web (SSR): `routes/web.php`
  - Grupo protegido `jwt.auth` -> `Route::resource('proyectos', ProyectoController::class)`
- API: `routes/api.php`
  - POST `/api/registro` -> `AuthController@registro`
  - POST `/api/login` -> `AuthController@login`
  - CRUD `/api/proyectos` (JWT) -> `ProyectoController`
  - GET `/api/usuario` (JWT) -> datos mínimos del usuario

## 6) Autenticación JWT
- Generación en `AuthController::generarJWT` (HS256, 24h).
- Middleware `App\Http\Middleware\JWTAuthMiddleware` (alias `jwt.auth`) acepta:
  - Header Authorization: Bearer <token>
  - Cookie `jwt_token` (para SSR). Las vistas de login/registro guardan token en localStorage y cookie.
- En error: respuesta 401 JSON en API o redirección a `/login` en web.

## 7) Modelos y migraciones
- `User` (correo único, clave hasheada vía mutator).
- `Proyecto` (campos: nombre, fecha_inicio, estado, responsable, monto, created_by FK a users).
- Migraciones en `database/migrations/*`.

## 8) Seeders (idempotentes)
- `UserSeeder`: crea/reutiliza admin@sistema.com.
- `ProyectoSeeder`: asegura admin y crea proyectos ejemplo con `firstOrCreate`.
- `DatabaseSeeder` llama a ambos.

## 9) Servicio UF
- `app/Services/UFService.php` consulta Mindicador y respaldo Santa.cl; maneja errores con logs.
- Componente Blade: `resources/views/components/uf-display.blade.php`.

## 10) Vistas
- Autenticación: `resources/views/auth/login.blade.php`, `auth/registro.blade.php`.
- Proyectos: `resources/views/proyectos/*` (index, create, edit, show).
- Layout principal: `resources/views/layouts/app.blade.php`.

## 11) Pruebas
- Configuración `phpunit.xml` usa SQLite en memoria (rápidas).
- Ejecuta: `php artisan test`.

## 12) Despliegue
- Archivo `api/index.php` preparado para entornos tipo Vercel (usa SQLite `/tmp`, migra y siembra `DatabaseSeeder`, redirige caches a `/tmp`).
- En hosting con MySQL, usar `.env` de producción y ejecutar migraciones/seeders con `--force`.

## 13) Checklist de rúbrica (referencias a código)
- Conexión MySQL 3307: `.env`, `config/database.php` (driver mysql) – Hecho.
- Migraciones correctas: `database/migrations/*` – Hecho.
- Seeders: no requeridos por la evaluación (no se ejecutan por defecto).
- Autenticación JWT: `AuthController`, `JWTAuthMiddleware`, paquete `firebase/php-jwt` – Hecho.
- Rutas protegidas: `routes/web.php` y `routes/api.php` con `middleware('jwt.auth')` – Hecho.
- Validaciones de datos: `AuthController@registro/login`, `ProyectoController@store/update` – Hecho.
- Seguridad: hash de claves, expiración JWT, manejo 401/redirect – Hecho.
- Integración externa (UF): `UFService` + componente Blade – Hecho.
- UI en español y comentada: `resources/views/*` + docblocks en controladores/modelos – Hecho.
- Documentación: `README.md` + `README_2.md` – Hecho.

## 14) Solución de problemas
- “Access denied for user 'root'@'localhost'”: usa `DB_HOST=127.0.0.1`, verifica puerto 3307, o crea `app_user` y actualiza `.env`.
- “Token inválido o expirado”: vuelve a iniciar sesión en `/login` (regenera cookie `jwt_token`).
- UF no responde: puede ser fallo temporal de APIs; revisar `storage/logs/laravel.log`.

---

Nota sobre la rúbrica: no pude leer el PDF adjunto desde esta sesión. Si compartes los criterios clave (items y ponderación), adapto este README_2 punto por punto con evidencias exactas (rutas, snippets y capturas si hace falta).
