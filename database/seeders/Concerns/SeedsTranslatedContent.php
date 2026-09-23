<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\Storage;

trait SeedsTranslatedContent
{
    /**
     * Turn [fr, en, de, zh] into {"fr": ..., "en": ..., "de": ..., "zh": ...}.
     *
     * @param  array<int, string>|null  $values
     * @return array<string, string>|null
     */
    protected function tr(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        return array_combine(array_keys(config('sabonea.locales')), $values);
    }

    /**
     * Copy an image of the original site into the public disk so it can be managed from the back-office.
     */
    protected function image(?string $file): ?string
    {
        if ($file === null) {
            return null;
        }

        $path = "sabonea/{$file}";

        if (! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, file_get_contents(public_path("img/sabonea/{$file}")));
        }

        return $path;
    }
}
