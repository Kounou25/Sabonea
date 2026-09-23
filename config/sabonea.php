<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Site locales
    |--------------------------------------------------------------------------
    |
    | Every locale the site can be served in. Which ones are actually online
    | is decided from the back-office (languages table). The reference locale
    | is mandatory for every content and is used as fallback when a
    | translation is missing.
    |
    */

    'locales' => [
        'fr' => 'Français',
        'en' => 'English',
        'de' => 'Deutsch',
        'zh' => '中文',
    ],

    'reference_locale' => 'fr',

    /*
    |--------------------------------------------------------------------------
    | First back-office administrator (created by the database seeder)
    |--------------------------------------------------------------------------
    */

    'admin' => [
        'email' => env('ADMIN_EMAIL', 'admin@sabonea.com'),
        'password' => env('ADMIN_PASSWORD'),
    ],

];
