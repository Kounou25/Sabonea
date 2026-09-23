<?php

namespace App\Models\Concerns;

use App\Support\Locales;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Attributes listed in $translatable are stored as jsonb: {"fr": "...", "en": "..."}.
 */
trait HasTranslations
{
    public function initializeHasTranslations(): void
    {
        $this->mergeCasts(array_fill_keys($this->translatable, 'array'));
    }

    /**
     * Value of a translatable attribute in the current locale, falling back to the reference locale.
     */
    public function t(string $attribute, ?string $locale = null): ?string
    {
        $values = $this->getAttribute($attribute);

        if (! is_array($values)) {
            return null;
        }

        $locale ??= app()->getLocale();
        $value = filled($values[$locale] ?? null) ? $values[$locale] : ($values[Locales::reference()] ?? null);

        return filled($value) ? $value : null;
    }

    /**
     * Same as t(), with inline markdown (**bold**, *italic*, [link](url)) rendered as safe HTML.
     */
    public function md(string $attribute, ?string $locale = null): HtmlString
    {
        $value = $this->t($attribute, $locale);

        if ($value === null) {
            return new HtmlString('');
        }

        return new HtmlString(rtrim(Str::inlineMarkdown($value, [
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ])));
    }

    /**
     * Online locales for which a filled reference text has no translation yet.
     *
     * @return array<int, string>
     */
    public function missingLocales(): array
    {
        $reference = Locales::reference();
        $missing = [];

        foreach ($this->translatable as $attribute) {
            $values = $this->getAttribute($attribute);

            if (! is_array($values) || blank($values[$reference] ?? null)) {
                continue;
            }

            foreach (Locales::active()->pluck('code') as $code) {
                if ($code !== $reference && blank($values[$code] ?? null)) {
                    $missing[$code] = $code;
                }
            }
        }

        return array_values($missing);
    }

    /**
     * @return array<int, string>
     */
    public function getTranslatableAttributes(): array
    {
        return $this->translatable;
    }
}
