<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query();

        if ($search) {
            $users = $users->where('name', 'LIKE', "%{$search}%")
                ->orWhere('rfc', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
        }

        $users = $users->paginate(5);

        return view('admin.users.index', ['users' => $users]);
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
