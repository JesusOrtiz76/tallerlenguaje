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
        $unidades = UnidadAdministrativa::orderBy('onombre')->get();

        return view('auth.register', compact('unidades'));
    }

    /**
     * Validación de datos de registro
     */
    protected function validator(array $data)
    {
        // Normalizamos aquí también
        $data['name'] = mb_strtoupper($data['name'] ?? '', 'UTF-8');
        $data['rfc']  = mb_strtoupper($data['rfc'] ?? '', 'UTF-8');

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],

            'rfc'  => [
                'required',
                'string',
                'size:13',
                // 4 letras + 6 dígitos + 3 alfanuméricos
                'regex:/^[A-ZÑ&]{4}\d{6}[A-Z0-9]{3}$/',
                // RFC completo siempre único
                'unique:r12users,orfc',
                // Validador extra para RFC "maquillados"
                function ($attribute, $value, $fail) use ($data) {
                    $rfc    = mb_strtoupper($value, 'UTF-8');
                    $prefix = substr($rfc, 0, 10); // 4 letras + fecha AAMMDD

                    $query = User::where('orfc', 'like', $prefix.'%')
                        ->where('orfc', '!=', $rfc); // excluir el mismo RFC

                    if (!empty($data['name'])) {
                        $query->where('name', mb_strtoupper($data['name'], 'UTF-8'));
                    }

                    if ($query->exists()) {
                        $fail('Ya existe una cuenta con tu mismo nombre y un RFC muy similar. '
                            .'Si ya te habías registrado, utiliza "Olvidé mi contraseña" o contacta al soporte.');
                    }
                },
            ],

            'sexo' => ['required', 'in:M,F'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // correo también único
                'unique:r12users,email',
            ],

            'email_confirmation' => [
                'required',
                'string',
                'email',
                'same:email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'oclave' => [
                'required',
                'string',
                'size:10',
                'regex:/^15[A-Z]{3}\d{4}[A-Z]$/',
                'exists:r12centrostrabajo,oclave',
            ],

            'unidadadministrativa_id' => [
                'required',
                'exists:r12unidadadministrativa,id',
            ],
        ], [
            'name.required'   => 'El nombre es obligatorio.',

            'rfc.required'    => 'El RFC es requerido.',
            'rfc.size'        => 'El RFC debe tener exactamente 13 caracteres.',
            'rfc.regex'       => 'El formato del RFC no es válido.',
            'rfc.unique'      => 'Ya existe una cuenta registrada con este RFC.',

            'email.required'  => 'El correo electrónico es obligatorio.',
            'email.email'     => 'El correo electrónico no tiene un formato válido.',
            'email.max'       => 'El correo electrónico no debe exceder 255 caracteres.',
            'email.unique'    => 'Ya existe una cuenta registrada con este correo electrónico.',

            'email_confirmation.required' => 'La confirmación del correo electrónico es obligatoria.',
            'email_confirmation.same'     => 'La confirmación del correo electrónico debe coincidir.',

            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',

            'oclave.required' => 'La clave del centro de trabajo es obligatoria.',
            'oclave.size'     => 'La clave del centro de trabajo debe tener exactamente 10 caracteres.',
            'oclave.regex'    => 'El formato de la clave del centro de trabajo no es válido.',
            'oclave.exists'   => 'La clave del centro de trabajo no existe en nuestros registros.',

            'sexo.required' => 'El sexo es obligatorio.',
            'sexo.in'       => 'El sexo seleccionado no es válido.',

            'unidadadministrativa_id.required' => 'La unidad administrativa es obligatoria.',
            'unidadadministrativa_id.exists'   => 'La unidad administrativa seleccionada no existe.',
        ]);
    }

    /**
     * Lógica de registro (AHORA solo crea si todo es nuevo)
     */
    public function register(Request $request)
    {
        // Normalizar entradas (no confiamos solo en JS)
        $request->merge([
            'name'               => mb_strtoupper($request->input('name', ''), 'UTF-8'),
            'rfc'                => mb_strtoupper($request->input('rfc', ''), 'UTF-8'),
            'oclave'             => mb_strtoupper($request->input('oclave', ''), 'UTF-8'),
            'email'              => mb_strtolower($request->input('email', ''), 'UTF-8'),
            'email_confirmation' => mb_strtolower($request->input('email_confirmation', ''), 'UTF-8'),
        ]);

        // Validar los datos → si RFC o email ya existen, aquí truena y regresa al form
        $this->validator($request->all())->validate();

        // Localizar centro de trabajo
        $centro = CentroTrabajo::where('oclave', $request->oclave)->first();

        // Crear usuario NUEVO (ya no actualizamos uno existente)
        $user = User::create([
            'name'                    => $request->name,
            'orfc'                    => $request->rfc,
            'sexo'                    => $request->sexo,
            'email'                   => $request->email,
            'password'                => Hash::make($request->password),
            'centrotrabajo_id'        => $centro ? $centro->id : null,
            'unidadadministrativa_id' => $request->unidadadministrativa_id,
        ]);

        // Si luego activas verificación de correo, descomentas esto:
        // $user->sendEmailVerificationNotification();

        // Aquí NO decimos "actualizamos datos", solo registro nuevo
        return redirect()
            ->route('login')
            ->with('success', '¡Registro exitoso! Ahora puedes iniciar sesión con tu RFC y contraseña.');
    }
}
