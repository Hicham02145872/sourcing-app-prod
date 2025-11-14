<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialMediaLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SocialMediaLinkController extends Controller
{
    public function edit()
    {
        $socialMediaLinks = SocialMediaLink::first();
        return view('admin.social-media-links.edit', compact('socialMediaLinks'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'whatsapp_message' => 'nullable|string',
        ]);

        $socialMediaLinks = SocialMediaLink::first();
        $socialMediaLinks->update($validated);

        return Redirect::route('admin.social-media-links.edit')->with('success', 'Social media links updated successfully.');
    }
}
