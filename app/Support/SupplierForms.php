<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Opening of the supplier forms and test mode.
 */
class SupplierForms
{
    public const PUBLIC_SETTING = 'supplier_form_public';

    /**
     * Form 1 is public once opened from the back-office; before that, only back-office users see it.
     */
    public static function contactFormIsOpen(): bool
    {
        return Setting::get(self::PUBLIC_SETTING) === '1' || auth()->check();
    }

    /**
     * Answers sent by a logged-in back-office user are tests: they are left out of the exports.
     */
    public static function isTestSubmission(): bool
    {
        return auth()->check();
    }
}
