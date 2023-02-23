<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'modulo_id',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

}
