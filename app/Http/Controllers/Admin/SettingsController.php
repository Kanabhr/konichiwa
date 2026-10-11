<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        $site = SiteSetting::allAsObject();

        return view('admin.settings.edit', compact('site'));
    }

    public function update(Request $request)
    {
        // Normalise WhatsApp before validating: strip spaces, hyphens and a leading +.
        $wa = preg_replace('/[\s\-\+]/', '', (string) $request->input('whatsapp_number', ''));
        $request->merge(['whatsapp_number' => $wa === '' ? null : $wa]);

        $validated = $request->validate([
            'phone_primary'   => ['nullable', 'string', 'max:20', 'regex:/^[\d\+\s\-]+$/'],
            'phone_secondary' => ['nullable', 'string', 'max:20', 'regex:/^[\d\+\s\-]+$/'],
            'email'           => ['nullable', 'email', 'max:255'],
            'whatsapp_number' => ['nullable', 'digits_between:10,15'],
            'address_reg'     => ['nullable', 'string', 'max:500'],
            'address_region'  => ['nullable', 'string', 'max:500'],
            'map_embed_url'   => ['nullable', 'url:https', 'max:2000', 'starts_with:https://www.google.com/maps/embed'],
            'facebook'        => ['nullable', 'url:http,https', 'max:255'],
            'instagram'       => ['nullable', 'url:http,https', 'max:255'],
            'youtube'         => ['nullable', 'url:http,https', 'max:255'],
            'linkedin'        => ['nullable', 'url:http,https', 'max:255'],
            'twitter'         => ['nullable', 'url:http,https', 'max:255'],
        ], [
            'phone_primary.regex'            => 'Phone may only contain digits, +, spaces and hyphens.',
            'phone_secondary.regex'          => 'Phone may only contain digits, +, spaces and hyphens.',
            'whatsapp_number.digits_between' => 'WhatsApp number must be 10 to 15 digits, including the country code (for example 919993955051).',
            'map_embed_url.starts_with'      => 'Paste only the URL from the src="..." part of the Google Maps embed code. It starts with https://www.google.com/maps/embed',
            'url'                            => 'Enter a full link starting with https:// or http://.',
        ]);

        SiteSetting::saveAll($validated);

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Contact details updated successfully.');
    }
}