<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

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

        $inactiveUsers = User::where('role', 'client')
                                ->whereNull('email_verified_at')
                                ->count();
        $newUsersThisMonth = User::where('role', 'client')
                                ->whereYear('created_at', now()->year)
                                ->whereMonth('created_at', now()->month)
                                ->count();
        $activeUsers = $query->count();
        $totalUsers = $query->count();
        $users = $query->paginate(10); // Paginate with 10 users per page

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.users.partials.users_table', compact('users'))->render(),
                'pagination' => view('admin.users.partials.pagination', compact('users'))->render(),
            ]);
        }

        return view('admin.users.index', compact('users', 'totalUsers', 'activeUsers', 'newUsersThisMonth', 'inactiveUsers'));
    }

    public function destroy(User $user): RedirectResponse
    {
        // Authorize the action
        // For example, using a policy or a simple check
        // $this->authorize('delete', $user); 

        // Prevent deleting super admin or own account for safety
        if ($user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Cannot delete a Super Admin.');
        }

        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}