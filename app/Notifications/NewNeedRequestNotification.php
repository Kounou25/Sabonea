<?php

namespace App\Notifications;

use App\Models\NeedRequest;
use App\Support\Countries;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewNeedRequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public NeedRequest $needRequest) {}

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
        $needRequest = $this->needRequest->loadMissing(['sector', 'equipmentType', 'deadline']);

        return (new MailMessage)
            ->subject("Nouvelle expression de besoin : {$needRequest->name}")
            ->greeting('Nouvelle expression de besoin')
            ->line("**Nom :** {$needRequest->name}")
            ->line('**Société :** '.($needRequest->company ?: '—'))
            ->line("**E-mail :** {$needRequest->email}")
            ->line('**Secteur :** '.($needRequest->sector?->t('name', 'fr') ?? 'Autre / non précisé'))
            ->line('**Équipement :** '.($needRequest->equipmentType?->t('name', 'fr') ?? 'Autre / non précisé'))
            ->line('**Pays :** '.(Countries::name($needRequest->country, 'fr') ?? '—'))
            ->line('**Délai :** '.($needRequest->deadline?->t('label', 'fr') ?? '—'))
            ->line('**Langue du site :** '.strtoupper($needRequest->locale))
            ->action('Voir la demande', url("/admin/need-requests/{$needRequest->id}"));
    }
}
