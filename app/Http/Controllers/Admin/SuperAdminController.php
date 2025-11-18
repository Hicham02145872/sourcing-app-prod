<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SuperAdminController extends Controller
{
    /**
     * Display the admin creation form.
     */
    public function createAdmin(): View
    {
        return view('admin.super-admin.create-admin');
    }

    /**
     * Store a newly created admin user.
     */
    public function storeAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', // Always create as 'admin'
            'email_verified_at' => now(), // Set email as verified
        ]);

        return redirect()->route('admin.super-admin.create-admin')->with('status', 'Admin user created successfully!');
    }

    /**
     * Display a listing of admin users.
     */
    public function listAdmins(): View
    {
        $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
        return view('admin.super-admin.list-admins', compact('admins'));
    }
}
