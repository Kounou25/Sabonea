<?php

namespace App\Support;

use App\Models\Setting;
use App\Models\SupplierApplication;
use App\Notifications\NewSupplierSubmissionNotification;
use App\Notifications\SupplierOnboardingLinkNotification;
use App\Notifications\SupplierSubmissionReceivedNotification;
use Illuminate\Support\Facades\Notification;

/**
 * E-mails of the supplier forms: after each form, an acknowledgement to the supplier (in the language of the form)
 * and a notification to the team (with a link to the file in the back-office); from the back-office, the private link.
 */
class SupplierMailer
{
    public const CONTACT_FORM = 'contact';

    public const ONBOARDING_FORM = 'onboarding';

    /**
     * After form 1 or form 2, once the thank-you message is displayed (the supplier does not wait for the mail server).
     * A mail server failure never affects the submission (it is logged).
     */
    public static function formSubmitted(SupplierApplication $application, string $form): void
    {
        Mailing::later(fn () => Notification::route('mail', self::teamEmail())
            ->notify((new NewSupplierSubmissionNotification($application, $form))->locale(Locales::reference())));

        $locale = $form === self::ONBOARDING_FORM ? $application->onboarding_locale : $application->contact_locale;

        Mailing::later(fn () => Notification::route('mail', self::supplierEmail($application))
            ->notify((new SupplierSubmissionReceivedNotification($application, $form))->locale(Locales::isActive((string) $locale) ? $locale : Locales::reference())));
    }

    /**
     * Private link to form 2, sent from the back-office right away: the team sees whether it left (errors are not hidden).
     */
    public static function sendOnboardingLink(SupplierApplication $application, ?string $email = null, ?string $locale = null): void
    {
        $locale = Locales::isActive((string) $locale) ? $locale : $application->preferredLocale();
        Mailing::allowTime();

        Notification::route('mail', $email ?: self::supplierEmail($application))
            ->notify((new SupplierOnboardingLinkNotification($application, $locale))->locale($locale));
    }

    /**
     * The e-mail given in form 2 (it may have been corrected), otherwise the one of form 1.
     */
    public static function supplierEmail(SupplierApplication $application): string
    {
        $corrected = $application->isOnboardingSubmitted() ? ($application->onboarding_answers['contact_email'] ?? null) : null;

        return filled($corrected) ? (string) $corrected : (string) $application->contact_email;
    }

    public static function teamEmail(): string
    {
        return (string) (Setting::get('notification_email') ?: Setting::get('contact_email'));
    }
}
