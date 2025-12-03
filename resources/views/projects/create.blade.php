<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Proyecto') }}
        </h2>
    </x-slot>

    <div class="py-12">
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

            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl">
                <div class="p-8 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('projects.store') }}" method="POST">
                        @csrf
                        
                        
                        <div class="mb-6">
                            <label for="name" class="block text-gray-600 text-sm font-bold mb-2">Nombre del Proyecto</label>
                            <input type="text" name="name" id="name" required placeholder="Ej: Página Web Corporativa"
                                class="border-gray-300 rounded-lg shadow-sm w-full focus:ring-orange-500 focus:border-orange-500 transition duration-150 p-3">
                        </div>

             
                        <div class="mb-6">
                            <label for="description" class="block text-gray-600 text-sm font-bold mb-2">Descripción</label>
                            <textarea name="description" id="description" rows="4" required placeholder="Describe brevemente el objetivo del proyecto..."
                                class="border-gray-300 rounded-lg shadow-sm w-full focus:ring-orange-500 focus:border-orange-500 transition duration-150 p-3"></textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-600 text-sm font-bold mb-2">Estado Inicial</label>
                            <input type="text" value="Agendado" disabled
                                class="border-gray-300 rounded-lg shadow-sm w-full bg-gray-100 text-gray-500 cursor-not-allowed font-semibold p-3">
                            
                            <p class="text-xs text-gray-400 mt-2 font-medium">
                                * Todo proyecto nuevo comienza automáticamente como Agendado.
                            </p>
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t border-gray-100 pt-6">
                            <a href="{{ route('projects.index') }}" class="text-sm text-gray-500 hover:text-gray-900 font-bold mr-6 transition duration-150">
                                Cancelar
                            </a>

                            <button type="submit" 
                                    style="background-color: #f97316; color: white;" 
                                    class="px-6 py-3 rounded-lg font-bold shadow-md hover:bg-orange-600 transition duration-150 text-white tracking-wide">
                                Crear Proyecto
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>