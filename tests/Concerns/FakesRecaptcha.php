<?php

namespace Tests\Concerns;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

trait FakesRecaptcha
{
    /**
     * reCAPTCHA keys configured, and Google confirming only the answer "human-answer".
     */
    protected function fakeRecaptcha(): void
    {
        config(['services.recaptcha.site_key' => 'site-key', 'services.recaptcha.secret_key' => 'secret-key']);

        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => fn (Request $request) => $request['response'] === 'human-answer'
                ? Http::response(['success' => true, 'hostname' => 'sabonea.com'])
                : Http::response(['success' => false, 'error-codes' => ['invalid-input-response']]),
        ]);
    }
}
