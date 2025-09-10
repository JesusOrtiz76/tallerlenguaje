<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'r12cursos';

    public function users()
    {
        return $this->belongsToMany(User::class, 'r12inscripciones', 'curso_id', 'user_id')
            ->withTimestamps();
    }

    public function modulos()
    {
        return $this->hasMany(Modulo::class);
    }

    public function inscripcionDetails()
    {
        return $this->hasMany(CursosUsersDetailView::class, 'curso_id');
    }

    public function userScore()
    {
        return $this->hasOne(UserScoreView::class, 'curso_id');
    }
}
