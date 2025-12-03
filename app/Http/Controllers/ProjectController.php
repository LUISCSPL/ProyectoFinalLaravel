<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $projects = Project::where('manager_id', $userId)
            ->orWhereHas('collaborators', function($query) use ($userId) {
                $query->where('users.id', $userId);
            })->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $reglaTexto = ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\sñÑáéíóúÁÉÍÓÚ.,]+$/u', 'not_regex:/^ +$/'];

        $request->validate([
            'name' => $reglaTexto,
            'description' => $reglaTexto,
        ]);

        Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'agendado',
            'manager_id' => Auth::id(),
        ]);

        return redirect()->route('projects.index')->with('success', 'Proyecto creado exitosamente.');
    }

    public function show(Project $project)
    {
        $userId = Auth::id();
        $isCollaborator = $project->collaborators->contains($userId);
        
        if ($project->manager_id != $userId && !$isCollaborator) {
            abort(403, 'No tienes permiso.');
        }

        $project->load(['tasks.assignedUser', 'collaborators']);
        
        $users = User::where('id', '!=', $project->manager_id)
                     ->whereNotIn('id', $project->collaborators->pluck('id'))
                     ->get();

        return view('projects.show', compact('project', 'users'));
    }

    public function updateProjectStatus(Request $request, Project $project)
    {
        if (Auth::id() != $project->manager_id) {
            return back()->with('error', 'Solo el encargado puede cambiar el estado del proyecto.');
        }

        if ($project->status == 'finalizado') {
            return back()->with('error', 'Este proyecto ya fue finalizado y no se puede modificar.');
        }

        $request->validate(['status' => 'required|in:agendado,en_proceso,finalizado']);
        
        if ($request->status == 'finalizado') {
            $incompleteTasks = $project->tasks()->where('status', '!=', 'finalizado')->exists();
            if ($incompleteTasks) {
                return back()->with('error', 'No puedes finalizar el proyecto hasta que todas las tareas estén terminadas.');
            }
        }

        $project->update(['status' => $request->status]);
        return back()->with('success', 'Estado del proyecto actualizado.');
    }

    public function addCollaborator(Request $request, Project $project)
    {
        if (Auth::id() != $project->manager_id) {
            return back()->with('error', 'Solo el encargado puede agregar colaboradores.');
        }
        $request->validate(['user_id' => 'required|exists:users,id']);
        $project->collaborators()->attach($request->user_id);
        return back()->with('success', 'Colaborador agregado.');
    }

    public function removeCollaborator(Project $project, User $user)
    {
        if (Auth::id() != $project->manager_id) {
            return back()->with('error', 'Solo el encargado puede eliminar colaboradores.');
        }
        $project->collaborators()->detach($user->id);
        return back()->with('success', 'Colaborador eliminado.');
    }

    public function storeTask(Request $request, Project $project)
    {
        if ($project->status == 'finalizado') {
            return back()->with('error', 'El proyecto está finalizado, no se pueden agregar más tareas.');
        }

        $reglaTexto = ['required', 'string', 'regex:/^[a-zA-Z0-9\sñÑáéíóúÁÉÍÓÚ.,]+$/u', 'not_regex:/^ +$/'];

        $request->validate([
            'name' => $reglaTexto,
            'description' => $reglaTexto,
            'assigned_to' => 'required|exists:users,id'
        ]);

        $assignedTo = $request->assigned_to;
        if (Auth::id() != $project->manager_id) {
            $assignedTo = Auth::id();
        }

        $project->tasks()->create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'agendado', 
            'assigned_to' => $assignedTo
        ]);

        return back()->with('success', 'Tarea creada.');
    }

    public function updateTaskStatus(Request $request, Task $task)
    {
        if ($task->status == 'finalizado') {
            return back()->with('error', 'Esta tarea ya fue finalizada y no se puede modificar.');
        }

        $request->validate(['status' => 'required|in:agendado,en_proceso,en_revision,finalizado']);

        if ($request->status == 'finalizado' && Auth::id() != $task->project->manager_id) {
            return back()->with('error', 'Solo el encargado del proyecto puede finalizar tareas.');
        }

        $task->update(['status' => $request->status]);
        
        return back()->with('success', 'Estado de tarea actualizado.');
    }

    public function editTask(Task $task)
    {
        if ($task->project->status == 'finalizado') {
            return redirect()->route('projects.show', $task->project_id)->with('error', 'El proyecto está finalizado, no se pueden editar tareas.');
        }

        if (Auth::id() != $task->project->manager_id && Auth::id() != $task->assigned_to) {
            abort(403, 'No tienes permiso para editar esta tarea.');
        }

        $users = $task->project->collaborators;
        
        return view('tasks.edit', compact('task', 'users'));
    }

    public function updateTask(Request $request, Task $task)
    {
        if ($task->project->status == 'finalizado') return back()->with('error', 'Proyecto finalizado.');
        if ($task->status == 'finalizado') return back()->with('error', 'Tarea finalizada no se edita.');

        $reglaTexto = ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\sñÑáéíóúÁÉÍÓÚ.,]+$/u', 'not_regex:/^ +$/'];

        $request->validate([
            'name' => $reglaTexto,
            'description' => $reglaTexto,
            'assigned_to' => 'required|exists:users,id'
        ]);

        $assignedTo = $request->assigned_to;
        if (Auth::id() != $task->project->manager_id) {
            $assignedTo = Auth::id();
        }

        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'assigned_to' => $assignedTo
        ]);

        return redirect()->route('projects.show', $task->project_id)->with('success', 'Tarea actualizada correctamente.');
    }

    public function destroyTask(Task $task)
    {
        if (Auth::id() != $task->project->manager_id) {
            return back()->with('error', 'Solo el encargado del proyecto puede eliminar tareas.');
        }

        if ($task->project->status == 'finalizado') {
            return back()->with('error', 'El proyecto está finalizado, no se puede eliminar nada.');
        }

        $task->delete();

        return back()->with('success', 'Tarea eliminada exitosamente.');
    }
}