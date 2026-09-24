<?php

namespace App\Support;

use Closure;
use Throwable;

class Mailing
{
    /**
     * Sends an e-mail without letting a mail server failure break the visitor's request:
     * the submission is already saved, the error is logged for the team.
     */
    public static function safely(Closure $send): bool
    {
        try {
            $send();

            return true;
        } catch (Throwable $exception) {
            report($exception);

            return false;
        }
    }
}
