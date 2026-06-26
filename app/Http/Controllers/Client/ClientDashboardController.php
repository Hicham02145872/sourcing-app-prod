<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    public function index(Request $request, string $locale)
    {
        $user = auth()->user();
        $query = $user->sourcingRequests()
            ->whereNotIn('status', ['cancelled', 'rejected']) // Exclude archived
            ->with('category', 'destinations.country', 'destinations.service');

        // Search by product name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'like', '%'.$search.'%');
                    });
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $status = $request->status;
            if ($status === 'action_required') {
                $query->where('status', 'quoted');
            } elseif ($status === 'processing') {
                $query->whereIn('status', ['pending', 'in_review', 'accepted']);
            } elseif ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date && $request->has('end_date') && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $sourcingRequests = $query->latest()->paginate(3)->withQueryString();

        $totalClientQuotations = $user->quotations()->count();
        $categories = Category::all();

        return view('client.dashboard', compact('sourcingRequests', 'totalClientQuotations', 'categories'));
    }
}
