<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Support\Facades\View;

class ModuloController extends Controller
{
    public function __construct()
    {
        $cursos = Curso::all();
        View::share('cursos', $cursos);
    }

    public function show(Modulo $modulo)
    {
        return "$modulo";
    }
}
