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
        'tiempo_lim',
        'numero_preguntas',
        'modulo_id',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'evaluacion_user')
            ->withPivot('intentos')
            ->withTimestamps();
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }

    public function resultado()
    {
        return $this->hasOne(Resultado::class);
    }
}
