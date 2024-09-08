<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroTrabajo extends Model
{
    use HasFactory;

    protected $table = 'r10centrostrabajo';

    public function users()
    {
        return $this->hasMany(User::class, 'centrotrabajo_id');
    }
}
