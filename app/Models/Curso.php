<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'r10cursos';

    public function users()
    {
        return $this->belongsToMany(User::class, 'r10inscripciones', 'curso_id', 'user_id')
            ->withTimestamps();
    }


    public function modulos()
    {
        return $this->hasMany(Modulo::class);
    }
}
