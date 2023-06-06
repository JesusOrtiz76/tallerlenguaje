<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'rfc' => ['required', 'string', 'max:13', 'min:13'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        $user = User::where('rfc', $data['rfc'])->first();

        if (!$user) {
            $rules['rfc'][] = 'unique:users';
        }

        return Validator::make($data, $rules, [
            'rfc.required' => 'El RFC es requerido.',
            'rfc.string' => 'El RFC debe ser una cadena de texto.',
            'rfc.max' => 'El RFC debe tener exactamente 13 caracteres.',
            'rfc.min' => 'El RFC debe tener exactamente 13 caracteres.',
            'rfc.unique' => 'El RFC ya ha sido registrado.',
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $userWithRfc = User::where('rfc', $request->rfc)->first();
        $userWithEmail = User::where('email', $request->email)->first();

        if ($userWithEmail && (!$userWithRfc || $userWithEmail->id != $userWithRfc->id)) {
            return redirect()->back()->withInput($request->all())->withErrors([
                'email' => "El correo electrónico ya está en uso."
            ]);
        }

        if ($userWithRfc && !$userWithRfc->hasVerifiedEmail()) {
            $userWithRfc->email = $request->email;
            $userWithRfc->save();

            $userWithRfc->sendEmailVerificationNotification();

            return redirect()->route('login')->with('success', '¡Registro exitoso! Se ha enviado un correo de verificación a tu dirección de correo electrónico.');
        } else if (!$userWithRfc) {
            $newUser = User::create([
                'name' => $request->name,
                'rfc' => $request->rfc,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $newUser->sendEmailVerificationNotification();

            return redirect()->route('login')->with('success', '¡Registro exitoso! Se ha enviado un correo de verificación a tu dirección de correo electrónico.');
        } else {
            return redirect()->back()->withInput($request->all())->withErrors([
                'rfc' => "El usuario con el RFC ya existe y su correo ha sido verificado."
            ]);
        }
    }
}
