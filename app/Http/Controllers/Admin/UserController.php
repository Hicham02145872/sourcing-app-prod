<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = User::where('role', 'client');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search . '%')
                  ->orWhere('email', 'like', $search . '%');
            });
        }

        $users = $query->paginate(10); // Paginate with 10 users per page

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.users.partials.users_table', compact('users'))->render(),
                'pagination' => view('admin.users.partials.pagination', compact('users'))->render(),
            ]);
        }

        return view('admin.users.index', compact('users'));
    }
}
