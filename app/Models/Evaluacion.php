<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'r12evaluaciones';

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'r12evaluacion_user', 'evaluacion_id', 'user_id')
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

    public function tema()
    {
        return $this->belongsTo(Tema::class, 'tema_id');
    }

    /**
     * Id de la evaluación "formal".
     */
    public const EVALUACION_FORMAL_ID = 17;

    /**
     * ¿Es la evaluación formal?
     */
    public function esEvaluacionFormal(): bool
    {
        return $this->id === self::EVALUACION_FORMAL_ID;
    }

    /**
     * Texto de tipo (singular) según si es evaluación o ejercicio.
     */
    public function etiquetaTipoSingular(): string
    {
        return $this->esEvaluacionFormal() ? 'Evaluación' : 'Ejercicio';
    }

    public function tituloConTipo(): string
    {
        $tipo = $this->etiquetaTipoSingular(); // Evaluación / Ejercicio
        $nombre = $this->onombre ?? '';

        // Quitamos espacios al inicio
        $nombreTrim = ltrim($nombre);

        // Si el nombre YA empieza con "Ejercicio" o "Evaluación", lo dejamos tal cual
        if (stripos($nombreTrim, $tipo) === 0) {
            return $nombreTrim;
        }

        // Si no, le anteponemos "Ejercicio: " o "Evaluación: "
        return $tipo . ': ' . $nombreTrim;
    }

}
