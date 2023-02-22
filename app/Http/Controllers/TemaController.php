<?php

namespace App\Http\Controllers;

use App\Models\Tema;

class TemaController extends Controller
{
    public function show(Tema $tema)
    {
        return $tema;
    }

}
