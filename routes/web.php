<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ModuloController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    //Cursos
    Route::get('cursos', [CursoController::class, 'index'])->name('cursos.index');
    Route::get('cursos/{curso}/modulo', [ModuloController::class, 'index'])->name('modulos.index');
    Route::post('cursos/{curso}/inscribirse', [CursoController::class, 'inscribirse'])->name('cursos.inscribirse');

    //Modulos
  
    Route::get('modulo/{modulo}', [ModuloController::class, 'show'])->name('modulos.show');

    //Temas
    Route::get('modulo/{modulo}/tema', [TemaController::class, 'index'])->name('temas.index');

    //Evaluaciones
    Route::get('modulo/{modulo}/evaluacion', [TemaController::class, 'store'])->name('evaluaciones.store');

    //Temas de estudio
    Route::get('modulos/{modulo}/tema', [ModuloController::class, 'index'])->name('temas.index');
    Route::get('tema/{tema}', [ModuloController::class, 'show'])->name('temas.show');
});
