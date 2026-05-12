# Task Manager Laravel

Aplicación de gestión de tareas desarrollada con Laravel. El proyecto incluye una interfaz web con Blade, autenticación de usuarios, operaciones CRUD sobre tareas y una API protegida por autenticación.

## Bloque 1. Instalación y ejecución del proyecto

Este proyecto puede ejecutarse de dos formas:

- en local con PHP, Composer, Node y MySQL instalados en tu máquina
- con Docker, usando la configuración que se ha añadido al repositorio

### Opción recomendada

Actualmente la forma más directa de levantar el proyecto es Docker, porque ya deja preparada la base de datos, los puertos y la ejecución de migraciones.

### Requisitos

#### Para ejecución local

- PHP 8.4
- Composer
- Node.js y npm
- MySQL 8

Extensiones PHP necesarias:

- `openssl`
- `fileinfo`
- `pdo_mysql`
- `mbstring`
- `tokenizer`
- `xml`
- `ctype`
- `json`
- `zip`

#### Para ejecución con Docker

- Docker Desktop
- Docker Compose
- `make` opcional, solo si quieres usar los atajos del `Makefile`

### Ejecución con Docker

#### 1. Entrar en la carpeta del proyecto

```bash
cd task-manager-laravel
```

#### 2. Levantar el entorno de desarrollo

Con `make`:

```bash
make dev-up
```

Sin `make`:

```bash
Copy-Item docker/.env.example docker/.env -Force
docker compose --env-file docker/.env -f docker/dev/docker-compose.yml up -d --build
```

Notas:

- Si `docker/.env` no existe y arrancas con `make`, el propio `Makefile` lo crea automáticamente desde `docker/.env.example`.
- El contenedor de Laravel espera a MySQL antes de arrancar.
- Las migraciones se ejecutan automáticamente mientras `RUN_MIGRATIONS=true` en `docker/.env`.

#### 3. Acceder a la aplicación

URLs principales:

- app web: `http://localhost:8000`
- healthcheck: `http://localhost:8000/up`
- MySQL expuesto en host: `localhost:3307`

Comportamiento de entrada:

- `/` redirige a `/tasks`
- si no hay sesión iniciada, Laravel te llevará al login

#### 4. Comandos útiles de Docker

```bash
make dev-logs
make dev-down
make dev-down-vol
make prod-up
make prod-down
```

### Ejecución en local sin Docker

#### 1. Entrar en la carpeta del proyecto

```bash
cd task-manager-laravel
```

#### 2. Instalar dependencias

```bash
composer install
npm install
```

#### 3. Crear el archivo de entorno

```powershell
Copy-Item .env.example .env -Force
```

#### 4. Revisar la configuración mínima del `.env`

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

#### 5. Generar la clave de la aplicación

```bash
php artisan key:generate
```

#### 6. Crear la base de datos

```sql
CREATE DATABASE task_manager;
```

#### 7. Ejecutar migraciones

```bash
php artisan migrate
```

#### 8. Compilar assets

Si quieres dejar los assets compilados:

```bash
npm run build
```

Si quieres trabajar con Vite en desarrollo:

```bash
npm run dev
```

#### 9. Levantar Laravel

```bash
php artisan serve
```

La aplicación quedará accesible en:

- `http://127.0.0.1:8000`

## Bloque 2. Estructura general del proyecto

### Web

La interfaz web principal está protegida con autenticación y usa Blade para renderizar las vistas. La raíz `/` redirige a `/tasks`.

### API

La API está definida en `routes/api.php` y expone operaciones CRUD de tareas bajo el prefijo `/api/tasks`, también protegidas por autenticación.

### Controladores

El proyecto separa responsabilidades entre:

- `TaskWebController` para la parte web
- `TaskController` para la parte API
- `AuthController` para login, registro y logout

### Servicios

La lógica de negocio se centraliza en servicios para evitar duplicación entre la capa web y la capa API.

### Persistencia

Las migraciones crean las tablas necesarias para:

- usuarios
- tareas
- cola de jobs
- caché en base de datos

## Bloque 3. Dockerización añadida

La app ha quedado dockerizada siguiendo como referencia la estructura de `apiTLOR` del workspace.

Archivos principales:

- `Dockerfile` multi-stage con targets `dev` y `prod`
- `docker/dev/docker-compose.yml`
- `docker/prod/docker-compose.yml`
- `docker/.env.example`
- `docker/scripts/entrypoint.sh`
- `Makefile`

La imagen de Docker usa PHP 8.4 y en producción compila también los assets con Vite.

## Bloque 4. Verificación rápida

Para comprobar que la aplicación está levantada con Docker:

```bash
curl http://localhost:8000/up
```

Si quieres comprobar además que los contenedores siguen activos:

```bash
docker compose --env-file docker/.env -f docker/dev/docker-compose.yml ps
```
# Task Manager Laravel

Aplicación de gestión de tareas desarrollada con Laravel. El proyecto combina una API REST y una interfaz web sencilla en Blade con una implementación de CRUD, validaciones, separación de responsabilidades y uso correcto de Eloquent.
