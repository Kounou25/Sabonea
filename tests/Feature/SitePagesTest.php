<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SitePagesTest extends TestCase
{
    /**
     * @return array<string, array{string, string}>
     */
    public static function pages(): array
    {
        return [
            'accueil' => ['accueil', 'Direct access to the best suppliers'],
            'a-propos' => ['a-propos', 'À propos de nous'],
            'comment-ca-fonctionne' => ['comment-ca-fonctionne', 'Comment ça marche'],
            'secteurs' => ['secteurs', 'Secteurs'],
            'pourquoi-sabonea' => ['pourquoi-sabonea', 'Pourquoi Sabonea'],
            'contact' => ['contact', 'Contact'],
            'expression-de-besoin' => ['expression-de-besoin', 'Exprimer un besoin'],
            'fournisseur-exemple' => ['fournisseur-exemple', 'Exemple de vitrine'],
        ];
    }

    #[DataProvider('pages')]
    public function test_page_is_displayed(string $routeName, string $expectedText): void
    {
        $response = $this->get(route($routeName));

        $response->assertOk();
        $response->assertSee($expectedText);
    }
}
