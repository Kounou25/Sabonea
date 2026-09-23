<?php

namespace App\Support;

use Illuminate\Support\Str;

class Media
{
    /**
     * Public URL of an image uploaded from the back-office (public disk).
     */
    public static function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/'.$path);
    }
}
