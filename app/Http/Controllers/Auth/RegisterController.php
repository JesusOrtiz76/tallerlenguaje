<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\CentroTrabajo;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        // Definir las reglas de validación
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'rfc' => ['required', 'string', 'size:13', 'unique:r10users,orfc'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:r10users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'oclave' => ['required', 'string', 'size:10', 'regex:/^15[A-Z]{3}\d{4}[A-Z]$/', 'exists:r10centrostrabajo,oclave'],
        ];

        // Mensajes de error personalizados
        $messages = [
            'oclave.required' => 'La clave del centro de trabajo es obligatoria.',
            'oclave.string' => 'La clave del centro de trabajo debe ser una cadena válida.',
            'oclave.size' => 'La clave del centro de trabajo debe tener exactamente 10 caracteres.',
            'oclave.regex' => 'El formato de la clave del centro de trabajo no es válido.',
            'oclave.exists' => 'La clave del centro de trabajo no existe.',
        ];

        return Validator::make($data, $rules, $messages);
    }

    public function register(Request $request)
    {
        // Validar los datos ingresados
        $this->validator($request->all())->validate();

        // Crear el nuevo usuario y asociarle el centro de trabajo
        $newUser = User::create([
            'name' => $request->name,
            'orfc' => $request->rfc,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'centrotrabajo_id' => CentroTrabajo::where('oclave', $request->oclave)->first()->id,
        ]);

        // Enviar la notificación de verificación de correo electrónico
        $newUser->sendEmailVerificationNotification();

        return redirect()->route('login')->with('success', '¡Registro exitoso! Se ha enviado un correo de verificación a tu dirección de correo electrónico.');
    }
}
