<?php

namespace App\Notifications;

use App\Models\Setting;
use App\Models\SupplierApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Private link to form 2 (onboarding file), sent from the back-office in the language chosen for the supplier.
 */
class SupplierOnboardingLinkNotification extends Notification
{
    use Queueable;

    public function __construct(public SupplierApplication $application, public string $linkLocale) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject(ui('mail.supplier_link.subject'))
            ->greeting(ui('mail.greeting', ['name' => $this->application->contact_name]))
            ->line(ui('mail.supplier_link.intro'))
            ->line(ui('mail.supplier_link.prefilled'))
            ->action(ui('mail.supplier_link.button'), (string) $this->application->onboardingUrl($this->linkLocale))
            ->line(ui('mail.supplier_link.validity', [
                'date' => $this->application->onboarding_token_expires_at?->locale($this->linkLocale)->isoFormat('LL') ?? '—',
            ]))
            ->salutation(ui('mail.signature'));

        if ($teamEmail = Setting::get('contact_email')) {
            $mail->replyTo($teamEmail, 'Sabonea');
        }

        return $mail;
    }
}
