<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = [
        'nombre', 'descripcion', 'duracion'
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
}
