<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AppliesDateRangeFilter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    use AppliesDateRangeFilter;

    public function index(Request $request): View|JsonResponse
    {
        $query = User::where('role', 'client');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        if ($request->has('fcm_status')) {
            if ($request->query('fcm_status') === 'has_token') {
                $query->whereNotNull('fcm_token');
            } elseif ($request->query('fcm_status') === 'no_token') {
                $query->whereNull('fcm_token');
            }
        }

        // Filter by creation date range (start / end)
        $this->applyDateRangeFilter($query, $request->query('date_debut'), $request->query('date_fin'));

        $inactiveUsers = User::where('role', 'client')
            ->whereNull('email_verified_at')
            ->count();
        $newUsersThisMonth = User::where('role', 'client')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $activeUsers = $query->count();
        $totalUsers = $query->count();
        $users = $query->with([
            'sourcingRequests' => function ($q) {
                $q->latest()->select('id', 'user_id', 'product_name', 'status', 'created_at');
            },
            'sourcingOrders' => function ($q) {
                $q->latest()->select('id', 'user_id', 'status', 'total_amount', 'created_at', 'quotation_id');
            },
        ])->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.users.partials.users_table_rows', compact('users'))->render(),
                'pagination' => view('admin.users.partials.pagination', compact('users'))->render(),
            ]);
        }

        return view('admin.users.index', compact('users', 'totalUsers', 'activeUsers', 'newUsersThisMonth', 'inactiveUsers'));
    }

    public function destroy(User $user): RedirectResponse
    {
        // Authorize the action
        if (! auth()->user()->canDeleteClients()) {
            return redirect()->back()->with('error', 'You do not have permission to delete clients.');
        }

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
