<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {

        $reglaTexto = ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9\sñÑáéíóúÁÉÍÓÚ]+$/u', 'not_regex:/^ +$/'];

        $request->validate([
            'name' => $reglaTexto,
            'lastname' => $reglaTexto,
            'username' => ['required', 'string', 'max:50', 'unique:'.User::class, 'regex:/^[a-zA-Z0-9\sñÑáéíóúÁÉÍÓÚ]+$/u'],
            
            
            'phone' => ['required', 'string', 'regex:/^\d{10}$/'], 
      

            'email' => ['required', 'string', 'lowercase', 'email', 'max:100', 'unique:users,correo'], 
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
           
            'phone.regex' => 'El teléfono debe tener exactamente 10 números.',
            'phone.required' => 'El teléfono es obligatorio.',
        ]);

        $user = User::create([
            'nombre' => $request->name,
            'apellido' => $request->lastname,
            'username' => $request->username,
            'telefono' => $request->phone,
            'correo' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}