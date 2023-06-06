<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Obtener cursos y módulos (Para el menú lateral)
    public function __construct()
    {
        $cursos = Curso::with('modulos')->get();
        View::share('cursos', $cursos);
    }
}
