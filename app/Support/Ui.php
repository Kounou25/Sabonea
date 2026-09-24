<?php

namespace App\Support;

use App\Models\UiTranslation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Interface strings (menu, buttons, form labels...) editable from the back-office.
 */
class Ui
{
    public const CACHE_KEY = 'sabonea.ui_translations';

    /**
     * @param  array<string, string>  $replace
     */
    public static function get(string $key, array $replace = [], ?string $locale = null): string
    {
        $texts = static::all()[$key] ?? [];
        $locale ??= app()->getLocale();

        $text = filled($texts[$locale] ?? null) ? $texts[$locale] : ($texts[Locales::reference()] ?? $key);

        foreach ($replace as $search => $value) {
            $text = str_replace(':'.$search, $value, $text);
        }

        return $text;
    }

    public static function has(string $key): bool
    {
        return array_key_exists($key, static::all());
    }

    /**
     * Interface string with inline markdown (**bold**, [link](url)) rendered as safe HTML; links open in a new tab.
     *
     * @param  array<string, string>  $replace
     */
    public static function md(string $key, array $replace = []): HtmlString
    {
        $html = rtrim(Str::inlineMarkdown(static::get($key, $replace), [
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]));

        return new HtmlString(str_replace('<a href=', '<a target="_blank" rel="noopener" href=', $html));
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function all(): array
    {
        return Cache::rememberForever(static::CACHE_KEY, fn () => UiTranslation::query()
            ->get(['key', 'text'])
            ->mapWithKeys(fn (UiTranslation $translation) => [$translation->key => $translation->text ?? []])
            ->all());
    }

    public static function flush(): void
    {
        Cache::forget(static::CACHE_KEY);
    }
}
