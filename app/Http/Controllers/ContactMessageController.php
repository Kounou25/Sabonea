<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Notifications\ContactMessageReceivedNotification;
use App\Notifications\NewContactMessageNotification;
use App\Support\Locales;
use App\Support\Mailing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class ContactMessageController extends Controller
{
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        // Honeypot: bots fill the hidden field, humans never see it.
        if ($request->filled('website')) {
            return to_route('contact')->withFragment('contact-form')->with('contact_sent', true);
        }

        $message = ContactMessage::create([
            ...$request->validated(),
            'locale' => app()->getLocale(),
            'ip_address' => $request->ip(),
        ]);

        Mailing::safely(fn () => Notification::route('mail', Setting::get('notification_email', Setting::get('contact_email')))
            ->notify((new NewContactMessageNotification($message))->locale(Locales::reference())));

        Mailing::safely(fn () => Notification::route('mail', $message->email)
            ->notify((new ContactMessageReceivedNotification($message))->locale($message->locale)));

        return to_route('contact')->withFragment('contact-form')->with('contact_sent', true);
    }
}
