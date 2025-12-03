<x-app-layout>
    <x-slot name="header">
        Proyecto: {{ $project->name }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success')) 
                <div id="alert-success" class="bg-green-100 border border-green-600 text-green-800 px-4 py-3 rounded-lg relative font-semibold shadow-md transition-opacity duration-1000 ease-out">
                    {{ session('success') }}
                </div> 
            @endif
            @if(session('error')) 
                <div id="alert-error" class="bg-red-100 border border-red-600 text-red-800 px-4 py-3 rounded-lg relative font-semibold shadow-md transition-opacity duration-1000 ease-out">
                    {{ session('error') }}
                </div> 
            @endif

            <script>
                setTimeout(function() {
                    let success = document.getElementById('alert-success');
                    let error = document.getElementById('alert-error');
                    if (success) { success.style.opacity = '0'; setTimeout(() => success.style.display = 'none', 1000); }
                    if (error) { error.style.opacity = '0'; setTimeout(() => error.style.display = 'none', 1000); }
                }, 3000);
            </script>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-2xl border-t-4 border-orange-500">
                        <h3 class="text-xl font-bold mb-4 border-b border-gray-200 pb-2 text-darkest">Información</h3>
                        <p class="text-sm text-secondary font-semibold mt-4">Descripción:</p>
                        <p class="mb-3 text-darkest">{{ $project->description }}</p>
                        <p class="text-sm text-secondary font-semibold">Encargado:</p>
                        <p class="font-bold text-darkest mb-3">{{ $project->manager->nombre }} {{ $project->manager->apellido }}</p>
                        <p class="text-sm text-secondary font-semibold">Estado:</p>
                        
                        @if(Auth::id() == $project->manager_id)
                            <form action="{{ route('projects.updateStatus', $project->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm text-sm font-bold focus:ring-orange-500 focus:border-orange-500">
                                    <option value="agendado" {{ $project->status == 'agendado' ? 'selected' : '' }}>Agendado</option>
                                    <option value="en_proceso" {{ $project->status == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                    <option value="finalizado" {{ $project->status == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                                </select>
                            </form>
                        @else
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold shadow-sm {{ $project->status == 'finalizado' ? 'bg-green-600 text-white' : 'bg-primary text-white' }} uppercase">
                                {{ str_replace('_', ' ', $project->status) }}
                            </span>
                        @endif
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-2xl border-t-4 border-orange-500">
                        <h3 class="text-xl font-bold mb-4 border-b border-gray-200 pb-2 text-darkest">Colaboradores</h3>
                        @if($project->collaborators->isEmpty())
                            <p class="text-sm text-secondary mb-4">No hay colaboradores aún.</p>
                        @else
                            <ul class="space-y-2 mb-4">
                                @foreach($project->collaborators as $collab)
                                    <li class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-100 shadow-sm">
                                        <div class="text-sm">
                                            <p class="font-bold text-darkest">{{ $collab->nombre }}</p>
                                            <p class="text-xs text-secondary">{{ $collab->correo }}</p>
                                        </div>
                                        @if(Auth::id() == $project->manager_id)
                                            <form action="{{ route('projects.removeCollaborator', [$project->id, $collab->id]) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs p-1" onclick="return confirm('¿Quitar a este colaborador?')">X</button>
                                            </form>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if(Auth::id() == $project->manager_id)
                            <div class="mt-4 border-t pt-4">
                                <form action="{{ route('projects.addCollaborator', $project->id) }}" method="POST">
                                    @csrf
                                    <label class="block text-sm font-bold text-secondary mb-2">Agregar usuario:</label>
                                    <div class="flex gap-2">
                                        <select name="user_id" class="border-gray-300 rounded-lg shadow-sm text-sm w-full focus:ring-orange-500 focus:border-orange-500">
                                            <option value="">Selecciona...</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->nombre }} ({{ $user->correo }})</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" style="background-color: #f97316; color: white;" class="px-4 py-2 rounded-lg text-sm font-bold shadow-md hover:bg-orange-600 transition">Agregar</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-2xl border-t-4 border-orange-500">
                        <h3 class="text-xl font-bold mb-4 border-b border-gray-200 pb-2 text-darkest">Tareas del Proyecto</h3>
                        
                        @if($project->tasks->isEmpty())
                            <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-secondary/40 mb-6">
                                <p class="text-secondary font-semibold">No hay tareas creadas todavía.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto mb-8 border border-secondary/20 rounded-lg shadow-md">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-800"> 
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase rounded-tl-lg">Tarea</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase">Responsable</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase">Estado</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase">Acciones</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase rounded-tr-lg">Cambiar</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($project->tasks as $task)
                                        <tr class="hover:bg-gray-50 transition duration-150">
                                            <td class="px-4 py-3 align-top">
                                                <span class="font-bold text-darkest block">{{ $task->name }}</span>
                                                <span class="text-xs text-secondary">{{ $task->description }}</span>
                                            </td>
                                            <td class="px-4 py-3 align-top text-sm text-darkest">
                                                {{ $task->assignedUser->nombre ?? 'Sin asignar' }}
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap shadow-sm
                                                    {{ $task->status == 'finalizado' ? 'bg-green-600 text-white' : 
                                                    ($task->status == 'en_revision' ? 'bg-orange-500 text-white' : 
                                                    ($task->status == 'en_proceso' ? 'bg-blue-600 text-white' : 'bg-gray-400 text-white')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            </td>
                                            
                                            <td class="px-4 py-3 align-top flex gap-2">
                                                @if(Auth::id() == $project->manager_id || Auth::id() == $task->assigned_to)
                                                    @if($task->status != 'finalizado')
                                                        <a href="{{ route('tasks.edit', $task->id) }}" class="text-blue-600 hover:text-blue-900 font-bold" title="Editar">
                                                            ✏️
                                                        </a>
                                                    @endif
                                                @endif

                                                @if(Auth::id() == $project->manager_id)
                                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold" title="Eliminar" onclick="return confirm('¿Eliminar esta tarea permanentemente?')">
                                                            🗑️
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>

                                            <td class="px-4 py-3 align-top">
                                                <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <select name="status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 w-full">
                                                        <option value="agendado" {{ $task->status == 'agendado' ? 'selected' : '' }}>Agendado</option>
                                                        <option value="en_proceso" {{ $task->status == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                                        <option value="en_revision" {{ $task->status == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                                                        @if(Auth::id() == $project->manager_id)
                                                            <option value="finalizado" {{ $task->status == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                                                        @endif
                                                    </select>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="bg-gray-50 p-5 rounded-xl border border-secondary/40 mt-8">
                            <h4 class="font-bold text-secondary mb-3 border-b border-gray-300 pb-1">Nueva Tarea</h4>
                            <form action="{{ route('projects.storeTask', $project->id) }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                    <div>
                                        <input type="text" name="name" placeholder="Nombre" class="border-gray-300 rounded-lg shadow-sm w-full focus:ring-orange-500 focus:border-orange-500" required />
                                    </div>
                                    <div>
                                        @if(Auth::id() == $project->manager_id)
                                            <select name="assigned_to" class="border-gray-300 rounded-lg shadow-sm text-sm w-full focus:ring-orange-500 focus:border-orange-500" required>
                                                <option value="{{ Auth::id() }}">Asignar a mí ({{ Auth::user()->nombre }})</option>
                                                @foreach($project->collaborators as $collab)
                                                    <option value="{{ $collab->id }}">{{ $collab->nombre }} {{ $collab->apellido }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="hidden" name="assigned_to" value="{{ Auth::id() }}">
                                            <input type="text" value="Asignado a: Mí mismo ({{ Auth::user()->nombre }})" class="border-gray-300 rounded-lg shadow-sm text-sm w-full bg-gray-200 text-gray-500 cursor-not-allowed" disabled>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="description" placeholder="Descripción breve" class="border-gray-300 rounded-lg shadow-sm w-full focus:ring-orange-500 focus:border-orange-500" required />
                                </div>
                                <button type="submit" style="background-color: #1f2937; color: white;" class="mt-2 px-4 py-2 rounded-lg text-sm font-bold shadow-md hover:bg-black transition">Guardar Tarea</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>