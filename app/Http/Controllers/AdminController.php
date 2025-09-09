<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::where('orol', '!=', 'admin')
            ->with('centroTrabajo:oclave,id')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('orfc', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhereHas('centroTrabajo', function ($query) use ($search) {
                            $query->where('oclave', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->paginate(10);

        return view('admin.users.index', ['users' => $users]);
    }
}
