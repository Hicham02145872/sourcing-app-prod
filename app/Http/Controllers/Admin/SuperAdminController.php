<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


class SuperAdminController extends Controller
{
    /**
     * Display the form for initial super admin registration.
     */
    public function createSuperAdminRegistrationForm(): View
    {
        return view('admin.super-admin.register');
    }

    /**
     * Handle an incoming super admin registration request.
     */
    public function registerSuperAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'super_admin',
            'email_verified_at' => now(), // Super Admin email is verified on creation
        ]);
        
        $user->markEmailAsVerified(); // Mark email as verified in the session

        Auth::login($user);

        return redirect()->route('admin.dashboard')->with('status', 'Super Admin created and logged in!');
    }
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

    /**
     * Display the specified admin user (API endpoint for modal).
     */
    public function showAdmin($id): JsonResponse
    {
        $admin = User::whereIn('role', ['admin', 'super_admin'])
                    ->findOrFail($id);
        
        return response()->json([
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role,
            'created_at' => $admin->created_at->toISOString(),
            'formatted_created_at' => $admin->created_at->format('d M Y, H:i')
        ]);
    }

    /**
     * Show the form for editing the specified admin user.
     */
    public function editAdmin(User $admin): View
    {
        // Ensure we're only editing admin users
        if (!in_array($admin->role, ['admin', 'super_admin'])) {
            abort(404);
        }
        
        return view('admin.super-admin.edit-admin', compact('admin'));
    }

    /**
     * Update the specified admin user in storage.
     */
    public function updateAdmin(Request $request, User $admin): RedirectResponse
    {
        // Ensure we're only updating admin users
        if (!in_array($admin->role, ['admin', 'super_admin'])) {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class . ',email,' . $admin->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        // Handle email verification status
        if ($request->has('verify_email')) {
            if (is_null($admin->email_verified_at)) {
                $admin->email_verified_at = now();
            }
        } else {
            $admin->email_verified_at = null;
        }

        $admin->save();

        return redirect()->route('admin.super-admin.list-admins')->with('status', 'Admin user updated successfully!');
    }

    /**
     * Remove the specified admin user from storage.
     */
    public function destroyAdmin(User $admin): RedirectResponse
    {
        // Ensure we're only deleting admin users
        if (!in_array($admin->role, ['admin', 'super_admin'])) {
            abort(404);
        }

        // Prevent deletion of the last super admin
        $superAdminCount = User::where('role', 'super_admin')->count();
        if ($admin->role === 'super_admin' && $superAdminCount <= 1) {
            return redirect()->route('admin.super-admin.list-admins')
                ->with('error', 'Cannot delete the last super admin user.');
        }

        $admin->delete();

        return redirect()->route('admin.super-admin.list-admins')->with('status', 'Admin user deleted successfully!');
    }
}