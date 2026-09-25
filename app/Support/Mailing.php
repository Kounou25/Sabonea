<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Sleep;
use Throwable;

use function Illuminate\Support\defer;

class Mailing
{
    /**
     * Seconds given to PHP for one sending: Microsoft 365 usually needs 5 to 10 s, sometimes much more,
     * and a web request is stopped after 30 s (the mail server timeout is set in config/mail.php).
     */
    private const TIME_LIMIT = 120;

    /**
     * Pauses (seconds) before trying again when the mail server does not answer: it is often only a moment.
     *
     * @var array<int, int>
     */
    private const RETRY_PAUSES = [3, 10];

    /**
     * Sends an e-mail without letting a mail server failure break the visitor's request:
     * the submission is already saved, the error is logged for the team.
     */
    public static function safely(Closure $send, bool $retry = false): bool
    {
        $pauses = $retry ? self::RETRY_PAUSES : [];

        while (true) {
            try {
                self::allowTime();
                $send();

                return true;
            } catch (Throwable $exception) {
                if ($pauses === []) {
                    report($exception);

                    return false;
                }

                self::resetConnection();
                Sleep::for(array_shift($pauses))->seconds();
            }
        }
    }

    /**
     * Same, once the page has been sent to the visitor (nobody waits for the mail server), with retries.
     */
    public static function later(Closure $send): void
    {
        defer(fn () => self::safely($send, retry: true));
    }

    /**
     * Restarts PHP's time counter before talking to the mail server.
     */
    public static function allowTime(): void
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(self::TIME_LIMIT);
        }
    }

    /**
     * Closes a connection left half-open by a failure, so that the next attempt starts afresh.
     */
    private static function resetConnection(): void
    {
        try {
            $transport = Mail::mailer()->getSymfonyTransport();

            if (method_exists($transport, 'stop')) {
                $transport->stop();
            }
        } catch (Throwable) {
            // Nothing to close.
        }
    }
}
