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
}
