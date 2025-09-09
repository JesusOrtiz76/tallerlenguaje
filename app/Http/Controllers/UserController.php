<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function updateUser(Request $request, $userId)
    {
        // Validar los datos
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Buscar o fallar si no existe el usuario
        $user = User::findOrFail($userId);
        $user->name  = $validatedData['name'];
        $user->email = $validatedData['email'];

        // Actualizar la contraseña solo si se proporcionó una nueva
        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->ochange_name = false;
        $user->save();

        return redirect()->back()->with('success', 'Datos actualizados correctamente.');
    }
}
