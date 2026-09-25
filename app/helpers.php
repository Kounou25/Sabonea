<?php

use App\Support\Ui;
use Illuminate\Support\Facades\Route;

if (! function_exists('ui')) {
    /**
     * Interface string editable from the back-office.
     *
     * @param  array<string, string>  $replace
     */
    function ui(string $key, array $replace = []): string
    {
        return Ui::get($key, $replace);
    }
}

if (! function_exists('locale_url')) {
    /**
     * URL of the current page in another language.
     */
    function locale_url(string $locale): string
    {
        $route = Route::current();

        if ($route?->getName() !== null && ! in_array('POST', $route->methods(), true)) {
            return route($route->getName(), ['locale' => $locale, ...$route->parameters()]);
        }

        return route('accueil', ['locale' => $locale]);
    }
}
