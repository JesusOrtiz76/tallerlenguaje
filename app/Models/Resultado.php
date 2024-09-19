<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    use HasFactory;

    protected $table = 'r12resultados';

    protected $fillable = [
        'user_id',
        'evaluacion_id',
        'orespuestas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class);
    }
}
