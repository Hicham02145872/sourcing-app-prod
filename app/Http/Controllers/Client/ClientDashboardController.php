<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class ClientDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = $user->sourcingRequests()->with('category', 'destinations.country', 'destinations.service');

        // Search by product name
        if ($request->has('search') && $request->search) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
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
