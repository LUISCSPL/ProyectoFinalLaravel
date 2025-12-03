<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Proyectos') }}
        </h2>
    </x-slot>

    <div class="py-6"> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
           
            @if(session('success')) 
                <div id="alert-success" class="bg-green-100 border border-green-600 text-green-800 px-4 py-3 rounded-lg relative font-semibold shadow-md transition-opacity duration-1000 ease-out mb-4">
                    {{ session('success') }}
                </div> 
            @endif
            @if(session('error')) 
                <div id="alert-error" class="bg-red-100 border border-red-600 text-red-800 px-4 py-3 rounded-lg relative font-semibold shadow-md transition-opacity duration-1000 ease-out mb-4">
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
           

            <div class="flex justify-end mb-4">
                
                <a href="{{ route('projects.create') }}" 
                   style="background-color: #f97316; color: white;" 
                   class="px-4 py-2 rounded-lg font-bold shadow-md hover:bg-orange-600 transition duration-150 text-sm">
                    + Nuevo Proyecto
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-t-4 border-orange-500">
                <div class="p-6 text-gray-900">
                    
                    @if($projects->isEmpty())
                        <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <p class="text-gray-500 text-lg font-medium">No tienes proyectos asignados aún.</p>
                            <p class="text-gray-400 text-sm mt-2">Crea uno nuevo para comenzar.</p>
                        </div>
                    @else
                        <h3 class="text-lg font-bold text-gray-700 mb-4">Proyectos Activos</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal shadow-sm rounded-lg overflow-hidden">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-bold uppercase tracking-wider">Proyecto</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-bold uppercase tracking-wider">Estado</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-bold uppercase tracking-wider">Encargado</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-bold uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($projects as $project)
                                    <tr class="hover:bg-gray-50 transition duration-150 border-b border-gray-200">
                                        <td class="px-5 py-5 text-sm">
                                            <p class="text-gray-900 font-bold text-base">{{ $project->name }}</p>
                                            <p class="text-gray-500 text-xs mt-1">{{ Str::limit($project->description, 50) }}</p>
                                        </td>
                                        <td class="px-5 py-5 text-sm">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold text-white shadow-sm
                                                {{ $project->status == 'finalizado' ? 'bg-green-600' : 
                                                  ($project->status == 'en_proceso' ? 'bg-blue-600' : 
                                                  'bg-orange-500') }}">
                                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-5 text-sm text-gray-700">
                                           
                                            {{ $project->manager->nombre }} {{ $project->manager->apellido }}
                                        </td>
                                        <td class="px-5 py-5 text-sm">
                                            <a href="{{ route('projects.show', $project->id) }}" class="text-orange-500 hover:text-orange-700 font-bold hover:underline transition duration-150">
                                                Ver Detalles
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>