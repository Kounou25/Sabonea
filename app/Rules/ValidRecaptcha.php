<?php

namespace App\Rules;

use App\Support\Recaptcha;
use App\Support\Ui;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * The "I'm not a robot" box is ticked and Google confirms it (see App\Support\Recaptcha).
 */
class ValidRecaptcha implements ValidationRule
{
    /**
     * Also checked when the box was not ticked (no answer at all).
     */
    public bool $implicit = true;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Recaptcha::passes($value)) {
            $fail(Ui::get('form.error_captcha'));
        }
    }
}
