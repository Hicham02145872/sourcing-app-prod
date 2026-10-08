<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnnouncementController extends Controller
{
    /**
     * List all announcements (super admin only — route gated).
     */
    public function index(?Announcement $announcement = null)
    {
        $announcements = Announcement::orderByDesc('start_date')->orderByDesc('id')->get();

        return view('admin.announcements.index', compact('announcements'))
            ->with('editing', $announcement);
    }

    public function edit(Announcement $announcement)
    {
        return $this->index($announcement);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['boolean'],
        ], [
            'end_date.after_or_equal' => __('The end date must be after or equal to the start date.'),
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')->with('success', __('Announcement created successfully.'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['boolean'],
        ], [
            'end_date.after_or_equal' => __('The end date must be after or equal to the start date.'),
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')->with('success', __('Announcement updated successfully.'));
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', __('Announcement deleted successfully.'));
    }
}
