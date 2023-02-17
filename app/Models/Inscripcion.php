<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';
    protected $fillable = ['user_id', 'curso_id', 'progreso'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function cursos()
    {
        return $this->belongsTo(Curso::class);
    }
}
