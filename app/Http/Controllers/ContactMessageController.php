<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Notifications\NewContactMessageNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class ContactMessageController extends Controller
{
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        // Honeypot: bots fill the hidden field, humans never see it.
        if ($request->filled('website')) {
            return to_route('contact')->with('contact_sent', true);
        }

        $message = ContactMessage::create([
            ...$request->validated(),
            'locale' => app()->getLocale(),
            'ip_address' => $request->ip(),
        ]);

        Notification::route('mail', Setting::get('notification_email', Setting::get('contact_email')))
            ->notify(new NewContactMessageNotification($message));

        return to_route('contact')->with('contact_sent', true);
    }
}
