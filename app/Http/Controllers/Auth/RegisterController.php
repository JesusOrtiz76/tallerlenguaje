<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\CentroTrabajo;
use App\Models\UnidadAdministrativa;
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

    /**
     * Mostrar formulario de registro con catálogos.
     */
    public function showRegistrationForm()
    {
        // Solo necesitamos el catálogo de unidades administrativas de momento
        $unidades = UnidadAdministrativa::orderBy('onombre')->get();

        return view('auth.register', compact('unidades'));
    }

    /**
     * Validación de datos de registro
     */
    protected function validator(array $data)
    {
        $rules = [
            'name'                   => ['required', 'string', 'max:255'],
            'rfc'                    => ['required', 'string', 'size:13'],
            'sexo'                   => ['required', 'in:M,F'],
            'email'                  => ['required', 'string', 'email', 'max:255'],
            'email_confirmation'     => ['required', 'string', 'email', 'same:email'],
            'password'               => ['required', 'string', 'min:8', 'confirmed'],
            'oclave'                 => ['required', 'string', 'size:10', 'regex:/^15[A-Z]{3}\d{4}[A-Z]$/', 'exists:r12centrostrabajo,oclave'],
            'unidadadministrativa_id'=> ['required', 'exists:r12unidadadministrativa,id'],
        ];

        // Validar si el RFC ya está registrado pero permitir cambiar el correo si no ha sido verificado
        $user = User::where('orfc', $data['rfc'])->first();
        if (!$user) {
            $rules['rfc'][] = 'unique:r12users,orfc';
        }

        return Validator::make($data, $rules, [
            'name.required'                    => 'El nombre es obligatorio.',
            'rfc.required'                     => 'El RFC es requerido.',
            'rfc.size'                         => 'El RFC debe tener exactamente 13 caracteres.',
            'email.required'                   => 'El correo electrónico es obligatorio.',
            'email.unique'                     => 'El correo electrónico ya está en uso.',
            'email_confirmation.same'          => 'La confirmación del correo electrónico debe coincidir.',
            'password.required'                => 'La contraseña es obligatoria.',
            'password.min'                     => 'La contraseña debe tener al menos 8 caracteres.',
            'oclave.required'                  => 'La clave del centro de trabajo es obligatoria.',
            'oclave.size'                      => 'La clave del centro de trabajo debe tener exactamente 10 caracteres.',
            'oclave.regex'                     => 'El formato de la clave del centro de trabajo no es válido.',
            'oclave.exists'                    => 'La clave del centro de trabajo no existe en nuestros registros.',
            'sexo.required'                    => 'El sexo es obligatorio.',
            'sexo.in'                          => 'El sexo seleccionado no es válido.',
            'unidadadministrativa_id.required' => 'La unidad administrativa es obligatoria.',
            'unidadadministrativa_id.exists'   => 'La unidad administrativa seleccionada no existe.',
        ]);
    }

    /**
     * Lógica de registro (manteniendo tu flujo de RFC + email)
     */
    public function register(Request $request)
    {
        // Validar los datos
        $this->validator($request->all())->validate();

        // Buscar usuario con el RFC ingresado
        $userWithRfc   = User::where('orfc', $request->rfc)->first();
        $userWithEmail = User::where('email', $request->email)->first();

        // Localizar centro de trabajo (puede ser null si algo raro pasa)
        $centro = CentroTrabajo::where('oclave', $request->oclave)->first();

        // Si el correo ya está en uso por otro usuario, se rechaza el registro
        if ($userWithEmail && (!$userWithRfc || $userWithEmail->id != $userWithRfc->id)) {
            return redirect()->back()->withInput($request->all())->withErrors([
                'email' => 'El correo electrónico ya está en uso.',
            ]);
        }

        // Si el usuario existe pero no ha verificado su correo, permitimos cambiar el correo
        if ($userWithRfc && !$userWithRfc->hasVerifiedEmail()) {
            $userWithRfc->email                  = $request->email;
            $userWithRfc->password               = Hash::make($request->password);
            $userWithRfc->sexo                   = $request->sexo;
            $userWithRfc->centrotrabajo_id       = $centro ? $centro->id : null;
            $userWithRfc->unidadadministrativa_id= $request->unidadadministrativa_id;
            $userWithRfc->save();

            // $userWithRfc->sendEmailVerificationNotification();

            return redirect()->route('login')->with('success', '¡Registro exitoso!');
        }

        // Si el usuario no existe, creamos uno nuevo
        if (!$userWithRfc) {
            $newUser = User::create([
                'name'                    => $request->name,
                'orfc'                    => $request->rfc,
                'sexo'                    => $request->sexo,
                'email'                   => $request->email,
                'password'                => Hash::make($request->password),
                'centrotrabajo_id'        => $centro ? $centro->id : null,
                'unidadadministrativa_id' => $request->unidadadministrativa_id,
            ]);

            // $newUser->sendEmailVerificationNotification();

            return redirect()->route('login')->with('success', '¡Registro exitoso!');
        }

        // Si el usuario ya existe y su correo está verificado
        return redirect()->back()->withInput($request->all())->withErrors([
            'rfc' => 'El usuario con este RFC ya existe y su correo ha sido verificado.',
        ]);
    }
}
