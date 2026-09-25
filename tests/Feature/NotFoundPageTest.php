<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\User;
use App\Support\Locales;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NotFoundPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
    }

    public function test_an_unknown_page_shows_the_404_page_in_the_language_of_the_address(): void
    {
        $this->get('/fr/page-qui-n-existe-pas')
            ->assertNotFound()
            ->assertSee('Cette page est introuvable')
            ->assertSee('Retour à l&#039;accueil', false)
            ->assertSee('href="'.url('/fr').'"', false)
            ->assertSee('<meta name="robots" content="noindex">', false)
            ->assertSee('<title>Page introuvable | Sabonea</title>', false);

        $this->get('/de/gibt-es-nicht')
            ->assertNotFound()
            ->assertSee('Diese Seite wurde nicht gefunden')
            ->assertSee('href="'.url('/de/contact').'"', false);
    }

    public function test_without_a_language_in_the_address_the_browser_language_is_used(): void
    {
        $this->get('/n-importe-quoi', ['Accept-Language' => 'zh-CN,zh;q=0.9,en;q=0.8'])
            ->assertNotFound()
            ->assertSee('找不到该页面')
            ->assertSee('<html lang="zh">', false);

        $this->get('/n-importe-quoi', ['Accept-Language' => 'es-ES,es;q=0.9'])
            ->assertNotFound()
            ->assertSee('Cette page est introuvable');
    }

    public function test_a_language_taken_offline_falls_back_to_the_browser_language(): void
    {
        Language::query()->where('code', 'de')->update(['is_active' => false]);
        Locales::flush();

        $this->get('/de/contact', ['Accept-Language' => 'en-GB,en;q=0.9'])
            ->assertNotFound()
            ->assertSee('This page cannot be found')
            ->assertSee('href="'.url('/en/contact').'"', false);
    }

    public function test_the_back_office_404_links_back_to_the_back_office(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/supplier-applications/999999')
            ->assertNotFound()
            ->assertSee('Retour au back-office')
            ->assertSee('href="'.url('/admin').'"', false);
    }
}
