<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateName(Request $request)
    {
        // Validar el nuevo nombre
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Actualizar el nombre del usuario autenticado
        $user = Auth::user();
        $user->name = $request->name;
        $user->ochange_name = true; // Cambiar el estado para indicar que el nombre fue confirmado
        $user->save();

        // Redirigir de nuevo a la página principal con un mensaje de éxito
        return redirect()->route('home')->with('success', 'Nombre actualizado correctamente.');
    }
}
