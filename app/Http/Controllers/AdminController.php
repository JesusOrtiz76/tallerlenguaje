<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CursosUsersDetailView;
use App\Models\User;
use App\Models\UserScoreView;
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

    public function show(User $user)
    {
        // Verifica que el usuario tenga el rol 'user'
        if ($user->orol !== 'user') {
            abort(404);
        }

        $cursos = Curso::with([
            'inscripcionDetails' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            },
            'userScore' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }
        ])->get();

        return view('admin.users.show', compact('user', 'cursos'));
    }
}
