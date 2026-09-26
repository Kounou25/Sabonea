<?php

namespace App\Http\Controllers;

use App\Enums\ContactMessageStatus;
use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Notifications\ContactMessageReceivedNotification;
use App\Notifications\NewContactMessageNotification;
use App\Support\Honeypot;
use App\Support\Locales;
use App\Support\Mailing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class ContactMessageController extends Controller
{
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        // Anti-spam trap: the message is kept (never lost if a browser filled it in), but listed apart and not notified.
        $spam = Honeypot::caught($request->input(Honeypot::FIELD), ['form' => 'contact', 'email' => $request->input('email')]);

        $message = ContactMessage::create([
            ...$request->validated(),
            'status' => $spam ? ContactMessageStatus::Spam : ContactMessageStatus::New,
            'locale' => app()->getLocale(),
            'ip_address' => $request->ip(),
        ]);

        if (! $spam) {
            Mailing::later(fn () => Notification::route('mail', Setting::get('notification_email', Setting::get('contact_email')))
                ->notify((new NewContactMessageNotification($message))->locale(Locales::reference())));

            Mailing::later(fn () => Notification::route('mail', $message->email)
                ->notify((new ContactMessageReceivedNotification($message))->locale($message->locale)));
        }

        return to_route('contact')->withFragment('contact-form')->with('contact_sent', true);
    }
}
