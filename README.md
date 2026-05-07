# Task Manager Laravel

Aplicación de gestión de tareas desarrollada con Laravel. El proyecto combina una API REST y una interfaz web sencilla en Blade con una implementación de CRUD, validaciones, separación de responsabilidades y uso correcto de Eloquent.

## Bloque 1. Instalación del proyecto

### Requisitos previos

Antes de arrancar el proyecto, la máquina debe tener instalado lo siguiente:

- PHP 8.2 o superior.
- Composer.
- Node.js y npm.
- MySQL.

También es recomendable tener habilitadas en PHP las extensiones habituales que Laravel necesita en este proyecto:

- `openssl`
- `fileinfo`
- `pdo_mysql`
- `mbstring`
- `tokenizer`
- `xml`
- `ctype`
- `json`

### Pasos para poner la app en marcha

1. Clonar o descargar el repositorio.

```bash
git clone git@github.com:RubenMoleroRios/task-manager-laravel.git
```

2. Entrar en la carpeta del proyecto.

```bash
cd task-manager-laravel
```

3. Instalar dependencias PHP con Composer.

```bash
composer install
```

4. Instalar dependencias front con npm.

```bash
npm install
```

5. Copiar el archivo `.env.example` y renombrarlo a `.env`.

```powershell
cp .env.example .env
```

### Configuración del archivo .env

Después de copiar `.env.example` a `.env`, hay que revisar al menos estos valores:

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

SESSION_DRIVER=file
```

### Nota sobre el archivo .env.example

Este proyecto requiere que el archivo `.env.example` se copie y se le quite el sufijo `.example`, dejándolo como `.env`. Ese paso es obligatorio porque Laravel carga su configuración principal desde `.env`.


6. Generar la clave de aplicación.

```bash
php artisan key:generate
```

7. Crear la base de datos en MySQL.

```sql
CREATE DATABASE task_manager;
```

8. Ejecutar migraciones y seeders.

```bash
php artisan migrate --seed
```

9. Compilar los assets o lanzar Vite en modo desarrollo.

Para compilar una versión lista para usar:

```bash
npm run build
```

Para desarrollo con recompilación automática:

```bash
npm run dev
```

10. Levantar el servidor de Laravel.

```bash
php artisan serve
```


### Assets front

La interfaz Blade carga CSS y JavaScript mediante Vite. Por eso, después de instalar dependencias front, hay dos opciones válidas:

- usar `npm run build` para dejar los assets compilados,
- usar `npm run dev` si se quiere trabajar en modo desarrollo.

Si solo se quiere ejecutar la app localmente sin tocar estilos o JavaScript, `npm run build` es suficiente.

### URL de acceso

Una vez arrancado el proyecto, la app queda accesible en:

- `http://127.0.0.1:8000`

La raíz redirige automáticamente a:

- `http://127.0.0.1:8000/tasks`


## Bloque 2. Mi compresión del proyecto.

### Routes

En las routes tenemos 2 archivos importantes, con uno trabajamos en la api y con el otro trabajamos en la web, en el de api por ejemplo, tenemos indicados todos los endpoints de la misma con sus respectivos alias. 

### Controladores

He decidido separar los controladores, para separar responsabilidades, ya que aunque ambos terminan llamando "TaskService" cada uno tiene su lógica interna, uno para API y otro para la vista de web con Blade. 

En el controlador de TaskWebController.php, podemos ver como trabajan las funciones siguiendo el flujo primero llamando al servicio, luego devuelve las vistas llamándolas por los alias a las distintas routes y gestionando los posibles errores que puedan surgir. 

Actualmente, el controlador que utiliza la aplicación, es "TaskWebController.php", también está creado el controlador "TaskController.php" preparado para utilizarlo en la api, pero actualmente no se utiliza.

He creado dos controladores para mostrar mis conocimientos sobre la reutilización de la lógica en "TaskService.php", separando responsabilidades, API y web.

### Servicios

Ambos controladores convergen aquí, aquí se ejecutan las acciones en la base de datos, utilizando la característica de eloquent.

### Vistas blade

En "index.blade.php" podemos ver el front de la aplicación, donde cargan los formularios y las tareas que se encuentren en la lista. Además de las funcionalidades de programación necesarias para las vistas de blade.

### Migraciones

En este archivo "create_task_table.php" observamos las funciones que utilizan las migraciones, para aplicar la migración o revertirla.


En mi github, tengo una APIREST la cual tengo dockerizada, esta no he decidido dockerizarla, porque quería una aplicación más simple, también, porque tengo entenido que no trabajáis con docker.

https://github.com/RubenMoleroRios/TheLordOfTheRingApi 