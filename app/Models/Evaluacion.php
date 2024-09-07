<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function resultado()
    {
        return $this->hasOne(Resultado::class);
    }
}
