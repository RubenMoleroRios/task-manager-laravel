<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * @var array<int, array{title: string, description: string}>
     */
    private const TASK_TEMPLATES = [
        [
            'title' => 'Revisar plan APPCC del cliente de hosteleria',
            'description' => 'Comprobar puntos criticos, registros de control y medidas correctoras antes de la auditoria interna.',
        ],
        [
            'title' => 'Preparar propuesta de implantacion ISO 9001',
            'description' => 'Definir alcance, mapa de procesos y cronograma inicial para una pyme de servicios.',
        ],
        [
            'title' => 'Actualizar registro de actividades de tratamiento RGPD',
            'description' => 'Revisar responsables, finalidades y medidas de seguridad para un cliente con varias sedes.',
        ],
        [
            'title' => 'Coordinar curso bonificado de prevencion de riesgos',
            'description' => 'Confirmar alumnos, documentacion y fechas con la empresa y el equipo de formacion.',
        ],
        [
            'title' => 'Revisar plan de igualdad pendiente de cierre',
            'description' => 'Validar diagnostico, objetivos y medidas con el departamento de recursos humanos.',
        ],
        [
            'title' => 'Preparar auditoria de seguimiento ISO 14001',
            'description' => 'Recopilar evidencias de gestion ambiental, indicadores y acciones correctivas abiertas.',
        ],
        [
            'title' => 'Enviar dossier de formacion en alternancia',
            'description' => 'Remitir propuesta comercial y requisitos del contrato de formacion a la empresa interesada.',
        ],
        [
            'title' => 'Revisar protocolo de desconexion digital',
            'description' => 'Ajustar el documento a la actividad del cliente y a sus politicas internas vigentes.',
        ],
        [
            'title' => 'Planificar visita tecnica a delegacion de Sevilla',
            'description' => 'Organizar desplazamiento, checklist documental y reunion con el responsable del centro.',
        ],
        [
            'title' => 'Verificar control de alergenos en industria alimentaria',
            'description' => 'Contrastar etiquetado, fichas tecnicas y procedimientos de limpieza con el cliente.',
        ],
        [
            'title' => 'Actualizar documentacion de seguridad contra incendios',
            'description' => 'Revisar mantenimientos, certificados y proxima fecha de inspeccion reglamentaria.',
        ],
        [
            'title' => 'Cerrar informe de orientacion laboral subvencionada',
            'description' => 'Registrar sesiones, objetivos alcanzados y acciones de acompanamiento para el expediente.',
        ],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $task = fake()->randomElement(self::TASK_TEMPLATES);

        return [
            'user_id' => User::factory(),
            'title' => $task['title'],
            'description' => fake()->boolean(85) ? $task['description'] : null,
            'completed' => fake()->boolean(25),
        ];
    }
}
