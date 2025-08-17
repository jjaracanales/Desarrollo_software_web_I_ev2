# Sistema de Gestión de Proyectos con Autenticación JWT

## 📋 Descripción

Sistema completo de gestión de proyectos desarrollado en Laravel 12 con autenticación JWT, integración de valor UF en tiempo real y gestión completa de usuarios y proyectos.

## ✨ Características Principales

### 🔐 Sistema de Autenticación
- **Registro de usuarios** con validación y hash de contraseñas
- **Inicio de sesión** con generación de JWT tokens
- **Middleware de autenticación** para proteger rutas
- **Gestión de sesiones** con tokens JWT

### 📊 Gestión de Proyectos
- **CRUD completo** de proyectos
- **Asociación con usuarios** (campo `created_by`)
- **Estados de proyecto**: Pendiente, En Progreso, Completado, Cancelado
- **Validación de datos** en formularios

### 💰 Integración UF
- **Valor UF en tiempo real** desde API Mindicador
- **API de respaldo** (Santa.cl)
- **Actualización automática** del valor
- **Sin valores hardcodeados** - solo datos reales

### 🎨 Interfaz de Usuario
- **Diseño moderno** con Ant Design
- **Vistas responsivas** para autenticación y proyectos
- **Estilos CSS personalizados** con gradientes y animaciones
- **Iconos FontAwesome** para mejor UX

## 🚀 Tecnologías Utilizadas

- **Backend**: Laravel 12 (PHP 8.2+)
- **Base de Datos**: MySQL
- **Autenticación**: JWT (Firebase PHP-JWT)
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Estilos**: Ant Design, CSS personalizado
- **Iconos**: FontAwesome 6.0

## 📁 Estructura del Proyecto

```
EV2/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php      # Controlador de autenticación
│   │   │   └── ProyectoController.php  # Controlador de proyectos
│   │   └── Middleware/
│   │       └── JWTAuthMiddleware.php   # Middleware JWT
│   ├── Models/
│   │   ├── User.php                    # Modelo de usuario
│   │   └── Proyecto.php                # Modelo de proyecto
│   └── Services/
│       └── UFService.php               # Servicio de valor UF
├── database/
│   ├── migrations/                     # Migraciones de BD
│   └── seeders/                        # Seeders con datos de ejemplo
├── resources/
│   └── views/
│       ├── auth/                       # Vistas de autenticación
│       ├── proyectos/                  # Vistas de proyectos
│       └── components/                 # Componentes reutilizables
└── routes/
    ├── api.php                         # Rutas de la API
    └── web.php                         # Rutas web
```

## 🗄️ Base de Datos

### Configuración
- **Host**: 127.0.0.1
- **Puerto**: 3307
- **Base de datos**: `desarrollo_software_1`
- **Usuario**: `root`
- **Contraseña**: `desarrollo_software_1`

### Tablas Principales

#### Users
- `id` - Identificador único
- `nombre` - Nombre completo del usuario
- `correo` - Correo electrónico (único)
- `clave` - Contraseña hasheada
- `created_at`, `updated_at` - Timestamps

#### Proyectos
- `id` - Identificador único
- `nombre` - Nombre del proyecto
- `fecha_inicio` - Fecha de inicio
- `estado` - Estado del proyecto
- `responsable` - Persona responsable
- `monto` - Monto del proyecto
- `created_by` - ID del usuario que creó el proyecto
- `created_at`, `updated_at` - Timestamps

## 🔌 API Endpoints

### Rutas Públicas
- `POST /api/registro` - Registro de usuario
- `POST /api/login` - Inicio de sesión

### Rutas Protegidas (requieren JWT)
- `GET /api/proyectos` - Listar proyectos
- `POST /api/proyectos` - Crear proyecto
- `GET /api/proyectos/{id}` - Ver proyecto específico
- `PUT /api/proyectos/{id}` - Actualizar proyecto
- `DELETE /api/proyectos/{id}` - Eliminar proyecto
- `GET /api/usuario` - Información del usuario autenticado

## 🛠️ Instalación y Configuración

### 1. Clonar el repositorio
```bash
git clone https://github.com/jjaracanales/Desarrollo_software_web_I_ev2.git
cd Desarrollo_software_web_I_ev2
```

### 2. Instalar dependencias
```bash
composer install
```

### 3. Configurar base de datos
- Crear base de datos MySQL: `desarrollo_software_1`
- Configurar credenciales en archivo `.env`

### 4. Ejecutar migraciones
```bash
php artisan migrate
```

### 5. (Opcional) Poblar base de datos
Esta evaluación no exige seeders. Si quieres datos de ejemplo, puedes ejecutar:
```bash
php artisan db:seed
```

### 6. Iniciar servidor
```bash
php artisan serve
```

## 👥 Usuarios de Ejemplo

### Credenciales de Acceso
1. **Administrador**
   - Email: `admin@sistema.com`
   - Contraseña: `admin123`

2. **Usuario Demo**
   - Email: `jose@ejemplo.com`
   - Contraseña: `password123`

3. **Usuario Adicional**
   - Email: `demo@ejemplo.com`
   - Contraseña: `demo123`

## 🔒 Seguridad

- **Contraseñas hasheadas** con Laravel Hash
- **JWT tokens** con expiración de 24 horas
- **Validación de datos** en todos los formularios
- **Middleware de autenticación** para rutas protegidas
- **Sanitización de inputs** automática

## 📱 Uso del Sistema

### 1. Acceso Inicial
- Navegar a `/login` o `/registro`
- Crear cuenta nueva o iniciar sesión

### 2. Gestión de Proyectos
- Ver lista de proyectos en `/proyectos`
- Crear nuevo proyecto con botón "Nuevo Proyecto"
- Editar/eliminar proyectos existentes

### 3. Valor UF
- Se muestra automáticamente en la página principal
- Se actualiza desde API externa
- Botón de actualización manual disponible

## 🧪 Testing

### Ejecutar Tests
```bash
php artisan test
```

### Verificar Funcionalidades
- Autenticación de usuarios
- CRUD de proyectos
- Validación de formularios
- Middleware de autenticación
- Servicio de valor UF

## 📊 Estadísticas del Proyecto

- **Total de archivos**: 70+
- **Líneas de código**: 12,000+
- **Controladores**: 2
- **Modelos**: 2
- **Vistas**: 6
- **Middleware**: 1
- **Servicios**: 1

## 👨‍💻 Autor

**José Jara Canales**
- Estudiante de Desarrollo de Software Web I
- Evaluación EV2 - Sistema de Gestión de Proyectos

## 📝 Licencia

Este proyecto es parte de una evaluación académica y está desarrollado con fines educativos.

## 🔄 Actualizaciones

- **v1.0**: Sistema base con gestión de proyectos
- **v2.0**: Integración de autenticación JWT
- **v3.0**: Servicio de valor UF en tiempo real
- **v4.0**: Middleware de autenticación y rutas protegidas

---

**¡Sistema completamente funcional y listo para usar!** 🎯
