<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Anti-spam trap of the public forms: a field hidden to visitors, that bots fill in.
 *
 * Its name and label mean nothing to browsers and password managers: a field named "website" or "fax"
 * could be filled in by their autofill, and the message of a real visitor taken for spam.
 * A caught submission is logged; the forms keep it with a "spam" status rather than dropping it.
 */
class Honeypot
{
    public const FIELD = 'leave_blank';

    /**
     * @param  array<string, mixed>  $context  what identifies the submission in the log (form, e-mail...)
     */
    public static function caught(mixed $value, array $context = []): bool
    {
        if (blank($value)) {
            return false;
        }

        Log::warning('Anti-spam trap filled in', [...$context, 'value' => Str::limit((string) $value, 80), 'ip' => request()->ip()]);

        return true;
    }
}
