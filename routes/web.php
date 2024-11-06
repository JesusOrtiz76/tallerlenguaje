<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\TemaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\RegisterController;

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

Route::get('/', [WelcomeController::class, 'index'])->name('/');

Auth::routes(['verify' => false, 'reset' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Aplicar el middleware checkregisterdate solo al registro
Route::get('register', [RegisterController::class, 'showRegistrationForm'])
    ->name('register')
    ->middleware('checkregisterdate');

Route::post('register', [RegisterController::class, 'register'])
    ->middleware('checkregisterdate');

// Ruta para verificar el certificado
Route::get('certificados/verify/{encodedParams}', [CursoController::class, 'verifyCertificado'])
    ->name('certificados.verify');

// Rutas para todos los usuarios autenticados
Route::middleware(['auth'])->group(function () {
    //Cursos
    Route::post('cursos/{curso}/inscribirse', [CursoController::class, 'inscribirse'])
        ->name('cursos.inscribirse');

    //Modulos
    Route::get('cursos/{curso}/modulos', [ModuloController::class, 'index'])
        ->name('modulos.index');

    // Certificados
    Route::get('cursos/{curso}/certificados', [CursoController::class, 'showCertificado'])
        ->name('certificados.show')
        ->middleware('checkNameChange');

    Route::get('modulos/{modulo}', [ModuloController::class, 'show'])
        ->name('modulos.show');

    //Temas
    Route::get('temas/{tema}', [TemaController::class, 'show'])
        ->name('temas.show');

    //Evaluaciones
    Route::get('evaluaciones/{evaluacion}', [EvaluacionController::class, 'show'])
        ->name('evaluaciones.show');

    Route::post('evaluaciones/{evaluacion}/submit', [EvaluacionController::class, 'submit'])
        ->name('evaluaciones.submit');

    //Resultados
    Route::get('evaluaciones/{evaluacion}/resultado', [EvaluacionController::class, 'resultado'])
        ->name('evaluaciones.resultado');

    // Ruta para actualizar el nombre
    Route::put('/user/update-name', [UserController::class, 'updateName'])->name('user.update_name');

});


// Solo para administradores
Route::middleware(['auth', 'checkUserRole:admin'])->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getDataIndex'])->name('dashboard.data');

    // Lista de usuarios registrados
    Route::get('users', [AdminController::class, 'index'])->name('admin.users');
});
