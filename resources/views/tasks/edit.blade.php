<x-app-layout>
    <x-slot name="header">
        Editar Tarea: {{ $task->name }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error')) 
                <div id="alert-error" class="bg-red-100 border border-red-600 text-red-800 px-4 py-3 rounded-lg mb-4">{{ session('error') }}</div> 
            @endif

            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border-t-4 border-orange-500">
                <div class="p-8 bg-white">
                    
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Tarea</label>
                            <input type="text" name="name" value="{{ $task->name }}" required
                                class="border-gray-300 rounded-lg shadow-sm w-full focus:ring-orange-500 focus:border-orange-500 p-3">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                            <input type="text" name="description" value="{{ $task->description }}" required
                                class="border-gray-300 rounded-lg shadow-sm w-full focus:ring-orange-500 focus:border-orange-500 p-3">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Responsable</label>
                            
                            @if(Auth::id() == $task->project->manager_id)
                                <select name="assigned_to" class="border-gray-300 rounded-lg shadow-sm w-full focus:ring-orange-500 focus:border-orange-500 p-3">
                                    <option value="{{ $task->project->manager_id }}" {{ $task->assigned_to == $task->project->manager_id ? 'selected' : '' }}>
                                        {{ $task->project->manager->nombre }} {{ $task->project->manager->apellido }} (Jefe)
                                    </option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                                            {{ $user->nombre }} {{ $user->apellido }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="assigned_to" value="{{ Auth::id() }}">
                                <input type="text" value="Yo ({{ Auth::user()->nombre }})" disabled
                                    class="border-gray-300 rounded-lg bg-gray-100 text-gray-500 w-full p-3 cursor-not-allowed">
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t border-gray-100 pt-6">
                            <a href="{{ route('projects.show', $task->project_id) }}" class="text-sm text-gray-500 hover:text-gray-900 font-bold mr-6">
                                Cancelar
                            </a>

                            <button type="submit" style="background-color: #f97316; color: white;" class="px-6 py-3 rounded-lg font-bold shadow-md hover:bg-orange-600 transition">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>