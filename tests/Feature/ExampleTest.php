<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The home page redirects to the visitor's language, then renders.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        Storage::fake('public');
        $this->seed();

        $this->get('/', ['Accept-Language' => 'fr-FR'])->assertRedirect('/fr');
        $this->get('/fr')->assertOk();
    }
}
