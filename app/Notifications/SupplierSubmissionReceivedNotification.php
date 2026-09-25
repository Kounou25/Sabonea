<?php

namespace App\Notifications;

use App\Models\Page;
use App\Models\Setting;
use App\Models\SupplierApplication;
use App\Support\SupplierMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Acknowledgement sent to the supplier after form 1 or form 2, in the language of the form.
 * It repeats the end message of the form ("Thank you" and "What happens next?"), edited in the back-office pages.
 */
class SupplierSubmissionReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(public SupplierApplication $application, public string $form) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isOnboarding = $this->form === SupplierMailer::ONBOARDING_FORM;
        $page = Page::query()
            ->where('key', $isOnboarding ? 'integration-fournisseur' : 'devenir-fournisseur')
            ->with('sections.items')
            ->first();

        $mail = (new MailMessage)
            ->subject(ui($isOnboarding ? 'mail.supplier_onboarding.subject' : 'mail.supplier_contact.subject'))
            ->greeting(ui('mail.greeting', ['name' => $this->application->contact_name]));

        if (filled($thanks = $page?->section('thanks')?->t('body'))) {
            $mail->line($thanks);
        }

        $nextSteps = $page?->section('next_steps');

        if ($nextSteps && $nextSteps->items->isNotEmpty()) {
            $mail->line('**'.$nextSteps->t('title').'**');

            foreach ($nextSteps->items as $index => $step) {
                $mail->line(($index + 1).'. **'.$step->t('title').'** — '.$step->t('text'));
            }
        }

        if ($teamEmail = Setting::get('contact_email')) {
            $mail->replyTo($teamEmail, 'Sabonea');
        }

        return $mail->salutation(ui('mail.signature'));
    }
}
