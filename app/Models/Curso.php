<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
    ];

    public function modulos()
    {
        return $this->hasMany(Modulo::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'inscripciones')
            ->withPivot('modulo_id')
            ->withTimestamps();
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class);
    }
}
