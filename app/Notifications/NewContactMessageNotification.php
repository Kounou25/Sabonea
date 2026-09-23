<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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

        return (new MailMessage)
            ->subject("Nouveau message de contact : {$contactMessage->name}")
            ->greeting('Nouveau message de contact')
            ->line("**Nom :** {$contactMessage->name}")
            ->line("**E-mail :** {$contactMessage->email}")
            ->line('**Objet :** '.($contactMessage->subject?->t('label', 'fr') ?? '—'))
            ->line('**Langue du site :** '.strtoupper($contactMessage->locale))
            ->action('Voir le message', url("/admin/contact-messages/{$contactMessage->id}"));
    }
}
