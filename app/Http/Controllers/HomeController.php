<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function calcularPorcentajePorCurso($user, $cursoId) {

        // Obtener todos los módulos del curso actual
        $totalModulos = Modulo::where('curso_id', $cursoId)->pluck('id');

        // Contar todas las evaluaciones para los módulos de ese curso
        $totalEvaluacionesCurso = Evaluacion::whereIn('modulo_id', $totalModulos)->count();

        // Contar todos los resultados de un usuario para las evaluaciones de esos módulos
        $totalResultadosCurso = $user->resultados()
            ->whereIn('evaluacion_id', Evaluacion::whereIn('modulo_id', $totalModulos)->pluck('id'))->count();

        // Calcula el porcentaje
        if($totalEvaluacionesCurso > 0) {
            return floor(($totalResultadosCurso / $totalEvaluacionesCurso) * 100);
        } else {
            return 0;
        }
    }

    public function index()
    {
        // Array vacío para almacenar porcentajes y llenados
        $porcentajes = [];
        $llenados = [];

        // Definiendo el radio del anillo de progreso (para el SVG).
        $radio = 16;

        // Calcular la circunferencia del anillo de progreso.
        $circunferencia = 2 * pi() * $radio;

        $cursos = Curso::all();

        if (Auth::check()) {

            // Obtener usuario autenticado.
            $user = Auth::user();

            // Iterar cursos y calcular el porcentaje de progreso de cada uno.
            foreach($cursos as $curso) {
                // Calcular porcentaje de progreso del usuario para el curso actual.
                $porcentajes[$curso->id] = $this->calcularPorcentajePorCurso($user, $curso->id);

                // Calcular el área llena del anillo para representar el progreso.
                $llenados[$curso->id] = $circunferencia - ($porcentajes[$curso->id] / 100 * $circunferencia);
            }
        }

        return view(
            'home', compact('cursos', 'porcentajes', 'radio', 'circunferencia', 'llenados')
        );
    }
}
