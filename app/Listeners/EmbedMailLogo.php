<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;

/**
 * Attaches the Sabonea logo to the e-mails whose header shows it ("cid:sabonea-logo", see the mail header view).
 * Embedded in the e-mail rather than linked: it displays in every mail client, even before the site is online.
 */
class EmbedMailLogo
{
    public const CID = 'sabonea-logo';

    public function handle(MessageSending $event): void
    {
        $email = $event->message;

        if (str_contains((string) $email->getHtmlBody(), 'cid:'.self::CID)) {
            $email->embedFromPath(public_path('img/sabonea/logo-sabonea-mail.png'), self::CID, 'image/png');
        }
    }
}
