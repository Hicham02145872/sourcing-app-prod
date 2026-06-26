<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Category::query();

        if ($search = $request->query('search')) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        $categories = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString(); // Paginate with 10 categories per page

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);
        // Create a new category
        Category::create($request->all());

        // Redirect to the categories index with a success message
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {   // Pass the category to the edit view
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {   // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
        ]);
        // Update the category with the validated data
        $category->update($request->all());

        // Redirect to the categories index with a success message
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Delete the category
        $category->delete();

        // Redirect to the categories index with a success message
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
