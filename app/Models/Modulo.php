<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'curso_id'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function temas()
    {
        return $this->hasMany(Tema::class);
    }

    public function evaluacion()
    {
        return $this->hasMany(Evaluacion::class);
    }
}
