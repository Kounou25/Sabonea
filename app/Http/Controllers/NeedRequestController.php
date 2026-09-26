<?php

namespace App\Http\Controllers;

use App\Enums\NeedRequestStatus;
use App\Http\Requests\StoreNeedRequestRequest;
use App\Models\NeedRequest;
use App\Models\Setting;
use App\Notifications\NewNeedRequestNotification;
use App\Support\Honeypot;
use App\Support\Locales;
use App\Support\Mailing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class NeedRequestController extends Controller
{
    public function store(StoreNeedRequestRequest $request): RedirectResponse
    {
        // Anti-spam trap: the request is kept (never lost if a browser filled it in), but listed apart and not notified.
        $spam = Honeypot::caught($request->input(Honeypot::FIELD), ['form' => 'need', 'email' => $request->input('email')]);

        $needRequest = NeedRequest::create([
            ...$request->validated(),
            'status' => $spam ? NeedRequestStatus::Spam : NeedRequestStatus::New,
            'locale' => app()->getLocale(),
            'ip_address' => $request->ip(),
        ]);

        if (! $spam) {
            Mailing::later(fn () => Notification::route('mail', Setting::get('notification_email', Setting::get('contact_email')))
                ->notify((new NewNeedRequestNotification($needRequest))->locale(Locales::reference())));
        }

        return to_route('expression-de-besoin')->with('need_sent', true);
    }
}
