<?php

use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\NeedRequestController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SupplierDocumentController;
use App\Http\Controllers\SupplierProfilePdfController;
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
    'devenir-fournisseur',
    'politique-de-confidentialite',
    'conditions-fournisseurs',
    'mentions-legales',
];

// Home: the language last chosen by the visitor, otherwise the browser's language.
Route::get('/', fn (Request $request) => to_route('accueil', [
    'locale' => Locales::negotiate([(string) $request->cookie(config('sabonea.locale_cookie')), ...$request->getLanguages()]),
]));

Route::prefix('{locale}')
    ->whereIn('locale', array_keys(config('sabonea.locales')))
    ->middleware(SetLocale::class)
    ->group(function () use ($pages): void {
        Route::get('/', PageController::class)->name('accueil');

        foreach ($pages as $page) {
            Route::get($page, PageController::class)->name($page);
        }

        // Supplier form 2: private link sent by Sabonea.
        Route::get('integration-fournisseur/{token}', PageController::class)
            ->where('token', '[A-Za-z0-9]{64}')
            ->name('integration-fournisseur');

        Route::post('contact', [ContactMessageController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('contact.store');

        Route::post('expression-de-besoin', [NeedRequestController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('expression-de-besoin.store');
    });

// Documents sent with supplier form 2: back-office users only.
Route::get('admin/documents-fournisseurs/{supplierApplication}/{field}/{index}', SupplierDocumentController::class)
    ->whereNumber('index')
    ->name('supplier-documents.download');

// Supplier profile sheet (PDF): back-office users only.
Route::get('admin/fiches-fournisseurs/{supplierApplication}', SupplierProfilePdfController::class)
    ->name('supplier-applications.profile-pdf');

// Addresses without language prefix (static site, legal pages): default language.
Route::get('{page}', fn (string $page) => redirect('/'.Locales::default()."/{$page}", 301))
    ->whereIn('page', $pages);
