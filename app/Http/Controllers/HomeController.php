<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $cursos = Curso::all();
        View::share('cursos', $cursos);

        $modulos = Modulo::all();
        View::share('modulos', $modulos);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
