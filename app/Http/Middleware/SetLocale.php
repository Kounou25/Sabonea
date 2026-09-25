<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Apply the {locale} route prefix, and 404 when that language is not online.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = (string) $request->route('locale');

        abort_unless(Locales::isActive($locale), 404);

        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);
        $request->route()->forgetParameter('locale');
        $request->attributes->set('locale', $locale);

        // Remembered for the home page redirect, so a returning visitor gets the language they chose.
        if ($request->cookie(config('sabonea.locale_cookie')) !== $locale) {
            Cookie::queue(config('sabonea.locale_cookie'), $locale, 60 * 24 * 365);
        }

        return $next($request);
    }
}
