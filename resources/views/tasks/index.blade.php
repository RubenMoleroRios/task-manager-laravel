<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Tareas</title>
    @vite(['resources/css/tasks.css', 'resources/js/tasks.js'])
</head>

<body>
    <div class="container">
        <h1>Gestor de Tareas</h1>

        @if (session('status'))
        <div class="status">
            {{ session('status') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="errors">
            <strong>Errores de validación:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="panel">
            <h2>Crear tarea</h2>

            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf

                <label for="title">Título</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" maxlength="255" required>

                <label for="description">Descripción</label>
                <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>

                <div class="checkbox-row">
                    <input id="completed" type="checkbox" name="completed" value="1" {{ old('completed') ? 'checked' : '' }}>
                    <label for="completed">Completada</label>
                </div>

                <button type="submit">Crear tarea</button>
            </form>
        </div>

        <div class="panel">
            <h2>Tareas</h2>

            @if ($tasks->isEmpty())
            <p class="empty">No hay tareas disponibles.</p>
            @else
            <ul>
                @foreach ($tasks as $task)
                <li>
                    <div class="task-row">
                        <div class="task-meta">
                            <span class="task-badge">{{ $task->completed ? 'Completada' : 'Pendiente' }}</span>
                            <div class="task-title {{ $task->completed ? 'completed' : '' }}">{{ $task->title }}</div>
                            <p class="task-description">{{ $task->description ?: 'No hay descripcion.' }}</p>

                            <div class="task-inline-actions">
                                <form method="POST" action="{{ route('tasks.toggle', $task->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="secondary">
                                        {{ $task->completed ? 'Marcar como pendiente' : 'Marcar como completada' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="danger">Eliminar</button>
                                </form>

                                <button
                                    type="button"
                                    class="edit-toggle {{ (string) old('edit_task_id') === (string) $task->id ? 'active' : '' }}"
                                    data-edit-toggle="task-{{ $task->id }}">
                                    Editar
                                </button>
                            </div>

                            <div id="task-{{ $task->id }}" class="task-edit-shell {{ (string) old('edit_task_id') === (string) $task->id ? '' : 'is-hidden' }}">
                                <form class="task-edit-form" method="POST" action="{{ route('tasks.update', $task->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="edit_task_id" value="{{ $task->id }}">

                                    <div class="task-edit-form-header">
                                        <h3 class="task-edit-form-title">Editar tarea</h3>
                                        <button type="button" class="task-edit-close" data-edit-close="task-{{ $task->id }}">Cerrar</button>
                                    </div>

                                    <div class="task-form-grid">
                                        <div>
                                            <label for="title-{{ $task->id }}">Editar titulo</label>
                                            <input id="title-{{ $task->id }}" type="text" name="title" value="{{ (string) old('edit_task_id') === (string) $task->id ? old('title', $task->title) : $task->title }}" maxlength="255" required>
                                        </div>

                                        <div>
                                            <label for="description-{{ $task->id }}">Editar descripcion</label>
                                            <textarea id="description-{{ $task->id }}" name="description" rows="3">{{ (string) old('edit_task_id') === (string) $task->id ? old('description', $task->description) : $task->description }}</textarea>
                                        </div>

                                        <div class="checkbox-row">
                                            <input id="completed-{{ $task->id }}" type="checkbox" name="completed" value="1" {{ ((string) old('edit_task_id') === (string) $task->id ? old('completed', $task->completed) : $task->completed) ? 'checked' : '' }}>
                                            <label for="completed-{{ $task->id }}">Completada</label>
                                        </div>

                                        <div>
                                            <button type="submit">Guardar cambios</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>

</body>

</html>