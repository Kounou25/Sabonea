<?php

use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\NeedRequestController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\SetLocale;
use App\Support\Locales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$pages = [
    'a-propos',
    'comment-ca-fonctionne',
    'secteurs',
    'pourquoi-sabonea',
    'contact',
    'expression-de-besoin',
    'fournisseur-exemple',
];

Route::get('/', fn (Request $request) => to_route('accueil', [
    'locale' => Locales::negotiate($request->getLanguages()),
]));

Route::prefix('{locale}')
    ->whereIn('locale', array_keys(config('sabonea.locales')))
    ->middleware(SetLocale::class)
    ->group(function () use ($pages): void {
        Route::get('/', PageController::class)->name('accueil');

        foreach ($pages as $page) {
            Route::get($page, PageController::class)->name($page);
        }

        Route::post('contact', [ContactMessageController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('contact.store');

        Route::post('expression-de-besoin', [NeedRequestController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('expression-de-besoin.store');
    });

// URLs of the original static site, without language prefix.
Route::get('{page}', fn (string $page) => redirect('/'.Locales::default()."/{$page}", 301))
    ->whereIn('page', $pages);
