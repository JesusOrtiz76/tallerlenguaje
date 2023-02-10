<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bloque extends Model
{
    protected $fillable = [
        'nombre', 'modulo_id'
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}
