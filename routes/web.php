<?php

use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\TemaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WelcomeController::class, 'index']);

Auth::routes(['verify' => true]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    //Cursos
    Route::get('cursos/{curso}/modulos', [ModuloController::class, 'index'])->name('modulos.index');
    Route::post('cursos/{curso}/inscribirse', [CursoController::class, 'inscribirse'])->name('cursos.inscribirse');

    //Modulos
    Route::get('modulos/{modulo}', [ModuloController::class, 'show'])->name('modulos.show');

    //Temas de estudio
    Route::get('temas/{tema}', [TemaController::class, 'show'])->name('temas.show');

    //Evaluaciones
    //Route::post('/evaluaciones', [EvaluacionController::class, 'store'])->name('evaluaciones.store');
    Route::get('evaluaciones/{evaluacion}', [EvaluacionController::class, 'show'])->name('evaluaciones.show');
    Route::post('modulos/{modulo}/evaluaciones/{evaluacion}/submit', [EvaluacionController::class, 'submit'])->name('evaluaciones.submit');

    //Resultados
    Route::get('modulos/{modulo}/evaluaciones/{evaluacion}/resultado', [EvaluacionController::class, 'resultado'])->name('evaluaciones.resultado');

});
