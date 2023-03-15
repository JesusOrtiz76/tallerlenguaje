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

    public function users()
    {
        return $this->belongsToMany(User::class, 'inscripciones')
            ->withPivot('progreso')
            ->withTimestamps();
    }

    public function modulos()
    {
        return $this->hasMany(Modulo::class);
    }
}
