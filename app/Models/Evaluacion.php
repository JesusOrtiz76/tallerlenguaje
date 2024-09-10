<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'r10evaluaciones';

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'r10evaluacion_user', 'evaluacion_id', 'user_id')
            ->withPivot('ointentos')
            ->withTimestamps();
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }

    public function resultados()
    {
        return $this->hasMany(Resultado::class);
    }

    // Verifica si el usuario ya no tiene más intentos
    public function sinIntentos()
    {
        $user = Auth::user();
        $pivot = $user->evaluaciones()->where('evaluacion_id', $this->id)->first();

        if ($pivot && $pivot->pivot->ointentos >= $this->ointentos_max) {
            return true;
        }

        return false;
    }

    // Retorna el intento actual del usuario
    public function intentoActual()
    {
        $user = Auth::user();
        $pivot = $user->evaluaciones()->where('evaluacion_id', $this->id)->first();

        if ($pivot) {
            return $pivot->pivot->ointentos + 1;
        }

        return 1; // Si no ha hecho intentos, es el primero
    }

    // Retorna los intentos restantes del usuario
    public function intentosRestantes()
    {
        $user = Auth::user();
        $pivot = $user->evaluaciones()->where('evaluacion_id', $this->id)->first();

        if ($pivot) {
            return $this->ointentos_max - $pivot->pivot->ointentos;
        }

        return $this->ointentos_max; // Si no ha hecho intentos, le quedan todos los intentos
    }
}
