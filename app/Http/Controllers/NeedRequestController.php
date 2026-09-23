<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNeedRequestRequest;
use App\Models\NeedRequest;
use App\Models\Setting;
use App\Notifications\NewNeedRequestNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class NeedRequestController extends Controller
{
    public function store(StoreNeedRequestRequest $request): RedirectResponse
    {
        // Honeypot: bots fill the hidden field, humans never see it.
        if ($request->filled('website')) {
            return to_route('expression-de-besoin')->with('need_sent', true);
        }

        $needRequest = NeedRequest::create([
            ...$request->validated(),
            'locale' => app()->getLocale(),
            'ip_address' => $request->ip(),
        ]);

        Notification::route('mail', Setting::get('notification_email', Setting::get('contact_email')))
            ->notify(new NewNeedRequestNotification($needRequest));

        return to_route('expression-de-besoin')->with('need_sent', true);
    }
}
