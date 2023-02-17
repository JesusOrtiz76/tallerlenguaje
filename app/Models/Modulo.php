<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $fillable = [
        'nombre', 'descripcion', 'curso_id', 'created_at'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
