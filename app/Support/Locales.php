<?php

namespace App\Support;

use App\Models\Language;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class Locales
{
    public const CACHE_KEY = 'sabonea.languages';

    /**
     * Every locale the site supports, whether online or not.
     *
     * @return array<string, string>
     */
    public static function all(): array
    {
        return config('sabonea.locales');
    }

    public static function reference(): string
    {
        return config('sabonea.reference_locale');
    }

    /**
     * Languages currently online, in display order.
     *
     * @return Collection<int, Language>
     */
    public static function active(): Collection
    {
        return static::languages()->where('is_active', true)->values();
    }

    /**
     * All languages (online or not), in display order.
     *
     * @return Collection<int, Language>
     */
    public static function languages(): Collection
    {
        // Plain arrays are cached: the cache store refuses to unserialize objects (cache.serializable_classes).
        $rows = Cache::rememberForever(static::CACHE_KEY, fn (): array => Language::query()
            ->orderBy('sort')
            ->get()
            ->map(fn (Language $language): array => $language->getAttributes())
            ->all());

        return Language::hydrate($rows);
    }

    public static function isActive(string $code): bool
    {
        return static::active()->contains('code', $code);
    }

    public static function default(): string
    {
        return static::active()->firstWhere('is_default', true)?->code
            ?? static::active()->first()?->code
            ?? static::reference();
    }

    /**
     * Pick the best online locale for the visitor's browser preferences.
     *
     * @param  array<int, string>  $preferred
     */
    public static function negotiate(array $preferred): string
    {
        foreach ($preferred as $candidate) {
            $code = strtolower(substr($candidate, 0, 2));

            if (static::isActive($code)) {
                return $code;
            }
        }

        return static::default();
    }

    /**
     * Language of a page that may be shown outside the localised routes (error pages): the one already applied
     * (App\Http\Middleware\SetLocale), then the /{locale}/ prefix of the address, the language chosen on a previous
     * visit, the browser preferences. The back-office is in French.
     */
    public static function detect(Request $request): string
    {
        if ($request->is('admin', 'admin/*')) {
            return static::reference();
        }

        $applied = $request->attributes->get('locale');

        if (is_string($applied) && static::isActive($applied)) {
            return $applied;
        }

        $prefix = strtolower((string) $request->segment(1));

        if (static::isActive($prefix)) {
            return $prefix;
        }

        return static::negotiate([static::rememberedLocale($request), ...$request->getLanguages()]);
    }

    /**
     * The language cookie, decrypted by hand when the request did not go through the web middleware (unknown address).
     */
    private static function rememberedLocale(Request $request): string
    {
        $name = (string) config('sabonea.locale_cookie');
        $value = (string) $request->cookies->get($name);

        if ($value === '' || static::isActive($value)) {
            return $value;
        }

        try {
            return (string) CookieValuePrefix::validate($name, Crypt::decryptString($value), Crypt::getAllKeys());
        } catch (Throwable) {
            return '';
        }
    }

    public static function flush(): void
    {
        Cache::forget(static::CACHE_KEY);
    }
}
