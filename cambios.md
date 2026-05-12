# Cambios realizados

## 1. Sistema de autenticacion basico

Se ha anadido un sistema de autenticacion sencillo basado en usuario, email y contrasena.

- El email es el identificador de acceso.
- No se permiten dos usuarios con el mismo email.
- El email se normaliza a minusculas antes de validar y autenticar para evitar duplicados por diferencias entre mayusculas y minusculas.

Archivos principales implicados:

- `app/Http/Controllers/AuthController.php`
- `app/Models/User.php`
- `config/auth.php`

## 2. Persistencia de usuarios

Se ha creado la tabla `users` con los campos necesarios para el login:

- `id`
- `name`
- `email` con restriccion `unique`
- `password`
- `remember_token`
- `timestamps`

Archivo de migracion:

- `database/migrations/2026_05_12_000004_create_users_table.php`

Ademas, se ha creado el modelo `User` y su factory para pruebas:

- `app/Models/User.php`
- `database/factories/UserFactory.php`

## 3. Registro de usuarios

Se ha implementado el formulario de registro con estos campos:

- Nombre
- Email
- Contrasena

Comportamiento:

- Valida que el nombre sea obligatorio.
- Valida que el email sea correcto, obligatorio y unico.
- Valida que la contrasena tenga al menos 8 caracteres.
- Tras registrarse, el usuario inicia sesion automaticamente.

Vista asociada:

- `resources/views/auth/register.blade.php`

Ruta asociada:

- `GET /register`
- `POST /register`

## 4. Login de usuarios

Se ha implementado el formulario de login con estos campos:

- Email
- Contrasena

Comportamiento:

- El acceso se realiza usando email y contrasena.
- Si las credenciales son correctas, se regenera la sesion y se redirige a la lista de tareas.
- Si las credenciales no son validas, se muestra error de validacion.

Vista asociada:

- `resources/views/auth/login.blade.php`

Ruta asociada:

- `GET /login`
- `POST /login`

## 5. Logout

Tambien se ha anadido el cierre de sesion:

- Se destruye la sesion actual.
- Se regenera el token CSRF.
- Se redirige al formulario de login.

Ruta asociada:

- `POST /logout`

## 6. Proteccion de rutas

Las rutas de tareas han quedado protegidas con el middleware `auth`.

Esto implica que:

- Un usuario no autenticado no puede entrar directamente al gestor de tareas.
- Las paginas de registro y login solo estan disponibles para invitados mediante el middleware `guest`.

Archivo implicado:

- `routes/web.php`

## 7. Cambios en la interfaz

Se ha creado una plantilla comun para compartir cabecera, mensajes de estado y errores.

Archivo principal:

- `resources/views/layouts/app.blade.php`

En la parte superior derecha ahora se muestran:

- Si el usuario no ha iniciado sesion: botones `Registrarse` y `Login`.
- Si el usuario ha iniciado sesion: nombre del usuario y boton `Cerrar sesion`.

Tambien se han ajustado estilos para soportar esta cabecera y los formularios de autenticacion.

Archivo de estilos:

- `resources/css/tasks.css`

## 8. Vista principal de tareas

La vista de tareas se ha adaptado para usar la nueva plantilla comun.

Archivo implicado:

- `resources/views/tasks/index.blade.php`

La lista y la gestion de tareas siguen funcionando, pero ahora dentro de una zona autenticada.

Ademas, cada usuario ve y gestiona unicamente sus propias tareas:

- Las tareas quedan asociadas al usuario autenticado mediante `user_id`.
- Un usuario no puede ver, editar ni borrar tareas de otro usuario.
- La lista mostrada en pantalla ya no es global, sino privada para cada cuenta.

Archivos implicados en este cambio:

- `app/Services/TaskService.php`
- `app/Models/Task.php`
- `app/Models/User.php`
- `database/migrations/2026_05_12_000005_add_user_id_to_tasks_table.php`

Nota:

- Las tareas antiguas que existieran antes de anadir `user_id` no quedaran asociadas automaticamente a un usuario.

## 9. Pruebas anadidas y ajustadas

Se han creado pruebas de autenticacion para verificar:

- Que un invitado sea redirigido al login al intentar abrir `/tasks`.
- Que un usuario pueda registrarse correctamente.
- Que no se puedan registrar emails duplicados aunque cambie el uso de mayusculas.
- Que un usuario pueda iniciar sesion con email y contrasena.

Archivo de pruebas:

- `tests/Feature/AuthTest.php`

Tambien se ajusto la prueba de ejemplo para reflejar el comportamiento actual de redirecciones.

- `tests/Feature/ExampleTest.php`

Tambien se anadieron pruebas para comprobar el aislamiento entre usuarios en la gestion de tareas.

- `tests/Feature/TaskOwnershipTest.php`
- `tests/Feature/TaskApiTest.php`

## 10. Validacion realizada

Se ejecuto la suite de pruebas del proyecto y quedo pasando tras adaptar el comportamiento esperado al nuevo flujo de autenticacion.

## 11. Nota importante

Para que estos cambios funcionen en local, debes tener aplicada la migracion de usuarios. Si aun no la has ejecutado, usa:

```bash
php artisan migrate
```