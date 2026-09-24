<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

/**
 * Sent to the Sabonea team for each message of the contact form. "Reply" answers the sender directly.
 */
class NewContactMessageNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public ContactMessage $contactMessage) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $contactMessage = $this->contactMessage->loadMissing('subject');
        $subject = $contactMessage->subject?->t('label', 'fr');

        return (new MailMessage)
            ->subject('Nouveau message de contact : '.$contactMessage->name.($subject ? " ({$subject})" : ''))
            ->replyTo($contactMessage->email, $contactMessage->name)
            ->greeting('Nouveau message de contact')
            ->line("**Nom :** {$contactMessage->name}")
            ->line("**E-mail :** {$contactMessage->email}")
            ->line('**Téléphone :** '.($contactMessage->phone ?: '—'))
            ->line('**Objet :** '.($subject ?? '—'))
            ->line('**Langue du site :** '.strtoupper($contactMessage->locale))
            ->line('**Message :**')
            ->line(new HtmlString(nl2br(e($contactMessage->message))))
            ->action('Voir le message dans le back-office', url("/admin/contact-messages/{$contactMessage->id}"))
            ->line('Répondez directement à cet e-mail pour écrire à l\'expéditeur.')
            ->salutation('Site Sabonea');
    }
}
