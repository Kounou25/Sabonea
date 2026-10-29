<?php

namespace App\Support;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Google reCAPTCHA v2 ("I'm not a robot" box) of the public forms, on top of the anti-spam trap.
 *
 * Active once both keys are in .env (RECAPTCHA_SITE_KEY, RECAPTCHA_SECRET_KEY): without them the forms work as before.
 * The widget is loaded from recaptcha.net, which Google serves where google.com is blocked (visitors from China).
 */
class Recaptcha
{
    /**
     * Field of the answer, added to the form by Google's script.
     */
    public const FIELD = 'g-recaptcha-response';

    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Error codes of Google meaning that the secret key itself is wrong, not the visitor's answer.
     */
    private const CONFIGURATION_ERRORS = ['missing-input-secret', 'invalid-input-secret'];

    public static function enabled(): bool
    {
        return filled(config('services.recaptcha.site_key')) && filled(config('services.recaptcha.secret_key'));
    }

    public static function siteKey(): string
    {
        return (string) config('services.recaptcha.site_key');
    }

    /**
     * Google's script, in the language of the page. $explicit: the widget is drawn by our script (Livewire form).
     */
    public static function scriptUrl(bool $explicit = false): string
    {
        $locale = app()->getLocale();
        $query = ['hl' => $locale === 'zh' ? 'zh-CN' : $locale];

        if ($explicit) {
            $query += ['render' => 'explicit', 'onload' => 'saboneaCaptchaLoaded'];
        }

        return 'https://www.recaptcha.net/recaptcha/api.js?'.http_build_query($query);
    }

    /**
     * Whether Google confirms the answer of the box (an answer is accepted only once).
     *
     * If Google cannot be reached or refuses the secret key, the submission is let through and logged: a real
     * visitor's message is never lost because of an outage or a wrong key, and the anti-spam trap and the
     * submission limits still apply.
     */
    public static function passes(mixed $answer): bool
    {
        if (! self::enabled()) {
            return true;
        }

        if (! is_string($answer) || blank($answer)) {
            return false;
        }

        try {
            $response = Http::asForm()->timeout(10)->post(self::VERIFY_URL, [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $answer,
                'remoteip' => request()->ip(),
            ])->throw();
        } catch (ConnectionException|RequestException $exception) {
            Log::warning('reCAPTCHA unreachable, submission accepted', ['error' => $exception->getMessage(), 'ip' => request()->ip()]);

            return true;
        }

        if ($response->json('success') === true) {
            return true;
        }

        if (array_intersect((array) $response->json('error-codes'), self::CONFIGURATION_ERRORS) !== []) {
            Log::error('reCAPTCHA secret key refused by Google (RECAPTCHA_SECRET_KEY in .env), submission accepted', ['errors' => $response->json('error-codes')]);

            return true;
        }

        return false;
    }
}
