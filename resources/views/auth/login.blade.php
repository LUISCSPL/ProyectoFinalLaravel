<x-guest-layout>
    <h2 class="text-3xl font-extrabold text-darkest text-center mb-6 pt-4">
        {{ __('Bienvenido de Nuevo') }} 
    </h2>

    <p class="text-center text-secondary mb-6 -mt-3">
        {{ __('Ingresa tus credenciales para acceder a tu cuenta.') }}
    </p>

    <div class="w-20 h-1 bg-primary mx-auto mb-8 rounded-full"></div> 

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" 
                          type="email" 
                          name="email" 
                          :value="old('email')" 
                          required 
                          autofocus 
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" 
                          type="password" 
                          name="password" 
                          required 
                          autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" name="remember">
                <span class="ms-2 text-sm text-secondary">Acuérdate de mí</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            <div class="text-sm space-y-2">
                <a href="{{ route('register') }}" class="block underline text-secondary hover:text-primary rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    ¿No tienes cuenta? Regístrate
                </a>
                
                @if (Route::has('password.request'))
                    <a class="block underline text-secondary hover:text-primary rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <x-primary-button type="submit" class="!py-3 !px-6 text-base">
                {{ __('Acceso') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>