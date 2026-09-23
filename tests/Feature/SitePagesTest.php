<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\Page;
use App\Support\Locales;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SitePagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function pages(): array
    {
        return [
            'accueil' => ['accueil', 'Direct access to the best suppliers'],
            'a-propos' => ['a-propos', 'À propos de nous'],
            'comment-ca-fonctionne' => ['comment-ca-fonctionne', 'Du besoin à la mise en relation'],
            'secteurs' => ['secteurs', 'Chantiers de construction'],
            'pourquoi-sabonea' => ['pourquoi-sabonea', 'Pourquoi choisir Sabonea'],
            'contact' => ['contact', 'Envoyez-nous un message'],
            'expression-de-besoin' => ['expression-de-besoin', 'Décrivez votre besoin en quelques champs'],
            'fournisseur-exemple' => ['fournisseur-exemple', 'Nom du fournisseur'],
        ];
    }

    #[DataProvider('pages')]
    public function test_page_is_displayed_in_french(string $routeName, string $expectedText): void
    {
        $response = $this->get(route($routeName, ['locale' => 'fr']));

        $response->assertOk();
        $response->assertSee($expectedText);
        $response->assertSee('<html lang="fr">', false);
    }

    #[DataProvider('pages')]
    public function test_page_is_displayed_in_every_language(string $routeName): void
    {
        foreach (['en', 'de', 'zh'] as $locale) {
            $this->get(route($routeName, ['locale' => $locale]))
                ->assertOk()
                ->assertSee("<html lang=\"{$locale}\">", false);
        }
    }

    public function test_pages_are_translated(): void
    {
        $this->get('/en/a-propos')->assertSee('Who we are')->assertSee('Home');
        $this->get('/de/a-propos')->assertSee('Wer wir sind')->assertSee('Startseite');
        $this->get('/zh/a-propos')->assertSee('我们是谁')->assertSee('首页');
    }

    public function test_missing_translation_falls_back_to_french(): void
    {
        $page = Page::query()->where('key', 'a-propos')->first();
        $page->update(['header_title' => ['fr' => 'À propos de nous', 'en' => '']]);

        $this->get('/en/a-propos')->assertSee('À propos de nous');
    }

    public function test_root_redirects_to_the_browser_language(): void
    {
        $this->get('/', ['Accept-Language' => 'de-DE,de;q=0.9'])->assertRedirect('/de');
        $this->get('/', ['Accept-Language' => 'es-ES'])->assertRedirect('/fr');
    }

    public function test_offline_language_is_not_served(): void
    {
        Language::query()->where('code', 'de')->update(['is_active' => false]);
        Locales::flush();

        $this->get('/de/secteurs')->assertNotFound();
        $this->get('/', ['Accept-Language' => 'de-DE'])->assertRedirect('/fr');
    }

    public function test_urls_of_the_static_site_redirect_to_the_default_language(): void
    {
        $this->get('/secteurs')->assertRedirect('/fr/secteurs')->assertStatus(301);
    }

    public function test_hidden_section_is_not_displayed(): void
    {
        $page = Page::query()->where('key', 'a-propos')->first();
        $page->sections()->where('key', 'story')->update(['is_visible' => false]);

        $this->get('/fr/a-propos')->assertDontSee('Une idée simple, portée par une vision plus grande');
    }

    public function test_pages_render_with_a_persistent_cache_store(): void
    {
        // Persistent stores serialize values and refuse to unserialize objects.
        config(['cache.default' => 'file']);
        Cache::flush();

        $this->get('/fr/secteurs')->assertOk();
        $this->get('/en/secteurs')->assertOk()->assertSee('Construction sites');

        Cache::flush();
    }

    public function test_language_switcher_links_to_the_same_page(): void
    {
        $this->get('/fr/secteurs')
            ->assertSee('href="'.url('/en/secteurs').'"', false)
            ->assertSee('hreflang="zh"', false);
    }
}
