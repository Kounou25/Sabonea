<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

/**
 * Acknowledgement sent to the visitor, in the language of the page the message was written from.
 */
class ContactMessageReceivedNotification extends Notification
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
     * Texts come from the interface strings: they are edited and translated from the back-office.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject(ui('mail.contact_received.subject'))
            ->greeting(ui('mail.greeting', ['name' => $this->contactMessage->name]))
            ->line(ui('mail.contact_received.intro'))
            ->line(ui('mail.contact_received.copy'))
            ->line(new HtmlString('<blockquote style="margin:0;padding:12px 16px;border-left:3px solid #49037F;background:#F2EAFB;color:#3d3450;">'.nl2br(e($this->contactMessage->message)).'</blockquote>'))
            ->salutation(ui('mail.signature'));

        if ($teamEmail = Setting::get('contact_email')) {
            $mail->replyTo($teamEmail, 'Sabonea');
        }

        return $mail;
    }
}
