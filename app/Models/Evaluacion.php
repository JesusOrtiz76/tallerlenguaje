<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones';

    protected $fillable = [
        'titulo',
        'descripcion',
        'tiempo_lim',
        'modulo_id',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'evaluacion_user')
            ->withPivot('intentos', 'resultados', 'completado')
            ->withTimestamps();
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }
}
