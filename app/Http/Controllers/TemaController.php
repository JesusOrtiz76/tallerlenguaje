<?php

namespace App\Http\Controllers;

use App\Models\Tema;
use App\Traits\VerificaAccesoTrait;
use App\Traits\VerificaEvaluacionesCompletasTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TemaController extends Controller
{
    use VerificaAccesoTrait, VerificaEvaluacionesCompletasTrait;

    public function show($temaId)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener el tema, módulo y curso
        $tema = Tema::with('modulo.curso')->findOrFail($temaId);
        $modulo = $tema->modulo;
        $curso = $tema->modulo->curso;

        // Verificar acceso usando el trait
        $resultado = $this->verificarAccesoCurso($user, $curso);
        if ($resultado['error']) {
            return redirect()->route('home')->with('warning', $resultado['message']);
        }

        // Verificar si hay algún módulo pendiente antes del actual
        $mensajePendiente = $this->verificarModuloPendiente($modulo);

        // Si hay un mensaje de módulo pendiente, mostrarlo
        if ($mensajePendiente) {
            return redirect()->back()->with('warning', $mensajePendiente);
        }

        // Mostrar el tema seleccionado
        return view('temas.show', compact('tema', 'modulo'));
    }
}
