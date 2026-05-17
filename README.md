# Task Manager Laravel

Aplicación de gestión de tareas construida con Laravel. El proyecto combina una interfaz web en Blade y una API REST para gestionar tareas por usuario, con autenticación, validación, separación de responsabilidades y soporte de ejecución tanto en local como con Docker.

## Resumen

Task Manager Laravel está orientado a demostrar una base sólida de aplicación full stack sobre Laravel:

- autenticación de usuarios con registro, login y logout
- gestión de tareas asociadas al usuario autenticado
- interfaz web server-rendered con Blade
- API REST bajo `/api/tasks`
- capa de servicios para reutilizar lógica entre web y API
- tests feature para autenticación, API y aislamiento por usuario
- entorno de desarrollo y producción mediante Docker

## Stack técnico

- Laravel 13
- PHP 8.3 o superior para desarrollo local
- Docker con imagen base PHP 8.4 para contenedores
- MySQL 8
- Blade para la interfaz web
- Vite y Tailwind CSS 4 para assets frontend
- PHPUnit para testing

## Funcionalidades principales

- registro e inicio de sesión de usuarios
- listado de tareas del usuario autenticado
- creación, edición y eliminación de tareas
- cambio de estado de completado desde la interfaz web
- endpoints REST para listar, crear, consultar, actualizar y eliminar tareas
- protección de acceso para impedir ver o modificar tareas de otros usuarios

## Puesta en marcha

### Opción recomendada: Docker

Esta es la forma más directa de levantar el proyecto porque deja preparados los puertos, la base de datos y la ejecución automática de migraciones.

#### Requisitos

- Docker Desktop
- Docker Compose
- `make` opcional para usar los atajos del `Makefile`

#### Arranque rápido

```bash
cd task-manager-laravel
make dev-up
```

Si no usas `make`:

```powershell
Copy-Item docker/.env.example docker/.env -Force
docker compose --env-file docker/.env -f docker/dev/docker-compose.yml up -d --build
```

#### Accesos por defecto

- aplicación web: `http://localhost:8000`
- health check: `http://localhost:8000/up`
- MySQL expuesto en host: `localhost:3307`

#### Comandos útiles

```bash
make dev-up
make dev-down
make dev-down-vol
make dev-restart
make dev-logs
make dev-build
make prod-up
make prod-down
make prod-logs
```

#### Notas operativas

- si `docker/.env` no existe, el `Makefile` lo genera automáticamente desde `docker/.env.example`
- el contenedor de aplicación espera a que MySQL esté saludable antes de arrancar
- las migraciones se ejecutan automáticamente mientras `RUN_MIGRATIONS=true`

### Ejecución local

#### Requisitos

- PHP 8.3 o superior
- Composer
- Node.js y npm
- MySQL 8

Extensiones PHP recomendadas:

- `openssl`
- `fileinfo`
- `pdo_mysql`
- `mbstring`
- `tokenizer`
- `xml`
- `ctype`
- `json`
- `zip`

#### Instalación

```bash
cd task-manager-laravel
composer install
npm install
```

#### Configuración inicial

```powershell
Copy-Item .env.example .env -Force
php artisan key:generate
```

Configura al menos estas variables de entorno:

```env
APP_NAME="Task Manager Laravel"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

Crea la base de datos y ejecuta migraciones:

```sql
CREATE DATABASE task_manager;
```

```bash
php artisan migrate
```

#### Desarrollo

Puedes lanzar el entorno completo de desarrollo con el script de Composer:

```bash
composer run dev
```

Ese script arranca:

- servidor Laravel
- listener de cola
- visor de logs con Pail
- Vite en modo desarrollo

Si prefieres ejecutar cada pieza por separado:

```bash
php artisan serve
npm run dev
```

Para compilar assets de producción:

```bash
npm run build
```

## Testing

El proyecto incluye pruebas automáticas para los flujos principales:

- autenticación y redirecciones
- API de tareas
- aislamiento de tareas por usuario

Ejecuta la suite con cualquiera de estas opciones:

```bash
composer test
```

```bash
php artisan test
```

## Arquitectura del proyecto

### Capa web

Las rutas web viven en `routes/web.php`. La raíz `/` redirige a `/tasks`, y la interfaz queda protegida por autenticación. La gestión visual de tareas se resuelve con Blade y `TaskWebController`.

### API

La API está definida en `routes/api.php` y expone operaciones CRUD sobre `/api/tasks`. La lógica de respuesta HTTP se concentra en `TaskController`.

### Lógica de negocio

La lógica compartida entre web y API se centraliza en `App\Services\TaskService`, evitando duplicación y manteniendo un único punto para las operaciones sobre tareas.

### Persistencia

Las migraciones incluidas cubren:

- usuarios
- tareas
- relación de tareas con usuario
- caché en base de datos
- cola de jobs

## Estructura relevante

```text
app/
	Http/Controllers/   Controladores web y API
	Models/             Modelos Eloquent
	Services/           Lógica de negocio
database/
	migrations/         Esquema de base de datos
resources/
	views/              Vistas Blade
	css/ y js/          Assets del frontend
routes/
	web.php             Rutas web
	api.php             Rutas API
tests/
	Feature/            Pruebas funcionales
docker/
	dev/ y prod/        Orquestación Docker
```

## Verificación rápida

Comprueba que la aplicación está disponible:

```bash
curl http://localhost:8000/up
```

Si estás usando Docker, también puedes revisar el estado de contenedores con:

```bash
docker compose --env-file docker/.env -f docker/dev/docker-compose.yml ps
```

## Mantenimiento

Si modificas dependencias, servicios o flujos de despliegue, actualiza este documento para que siga reflejando el estado real del proyecto.
