<?php

namespace App\Http\Controllers;

use App\Mail\NewEnquiry;
use App\Models\ContactMessage;
use App\Rules\MaxWords;
use App\Rules\Turnstile;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contactus');
    }

    public function store(Request $request)
    {
        $thanks = 'Thank you for your message! We will get back to you soon.';

        // Honeypot: real visitors never see this field. Pretend success, store nothing.
        if ($request->filled('website_url')) {
            return $request->ajax() ? response($thanks, 200) : back()->with('success', $thanks);
        }

        // Normalise the GST number (trim, uppercase, empty becomes null) before validating.
        $gst = strtoupper(trim((string) $request->input('gst_number', '')));
        $request->merge(['gst_number' => $gst === '' ? null : $gst]);

        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|max:255',
            'phone'                 => 'nullable|string|max:20',
            'subject'               => 'nullable|string|max:255',
            'gst_number'            => ['nullable', 'string', 'size:15', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/'],
            'message'               => ['required', 'string', new MaxWords(100), 'max:1000'],
            'cf-turnstile-response' => ['required', 'string', new Turnstile],
        ], [
            'cf-turnstile-response.required' => 'Please complete the security challenge.',
            'gst_number.size'                => 'Enter a valid 15-character GST number (for example 22AAAAA0000A1Z5).',
            'gst_number.regex'               => 'Enter a valid 15-character GST number (for example 22AAAAA0000A1Z5).',
        ]);

        $contactMessage = ContactMessage::create(Arr::except($validated, ['cf-turnstile-response']));

        // Send enquiry notification email.
        // A mail problem must never lose the saved enquiry, so it is only logged.
        $to = config('mail.enquiry_to');

        if (! $to || str_contains($to, 'PLACEHOLDER')) {
            Log::warning('ENQUIRY_TO_EMAIL is not configured; enquiry ' . $contactMessage->id . ' was saved but not emailed.');
        } else {
            try {
                Mail::to($to)->send(new NewEnquiry($contactMessage));
            } catch (\Throwable $e) {
                Log::error('NewEnquiry mail failed: ' . $e->getMessage(), [
                    'contact_message_id' => $contactMessage->id,
                ]);
            }
        }

        if ($request->ajax()) {
            return response($thanks, 200);
        }

        return back()->with('success', $thanks);
    }
}