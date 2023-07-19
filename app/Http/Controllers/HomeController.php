<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Comprobar si el usuario está autenticado
        if (Auth::check()) {
            $user = Auth::user();
            $totalEvaluaciones = Evaluacion::count();
            $totalResultados = $user->resultados()->count();

            // Evitamos la división por cero
            if($totalEvaluaciones > 0){
                $porcentaje = floor(($totalResultados / $totalEvaluaciones) * 100);
            }else{
                $porcentaje = 0;
            }
        } else {
            // Si el usuario no está autenticado, establecemos el porcentaje en 0
            $porcentaje = 0;
        }

        $radio = 16;
        $circunferencia = 2 * pi() * $radio;
        $llenado = $circunferencia - ($porcentaje / 100 * $circunferencia);

        return view('home', compact('porcentaje', 'radio', 'circunferencia', 'llenado'));
    }
}
