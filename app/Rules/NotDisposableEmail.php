<?php

namespace App\Rules;

use App\Support\Ui;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

/**
 * Rejects addresses of disposable email services (list in config/disposable_email_domains.php).
 */
class NotDisposableEmail implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! str_contains($value, '@')) {
            return;
        }

        $domain = Str::lower(Str::afterLast($value, '@'));

        foreach (config('disposable_email_domains') as $disposable) {
            if ($domain === $disposable || str_ends_with($domain, '.'.$disposable)) {
                $fail(Ui::get('form.error_disposable_email'));

                return;
            }
        }
    }
}
