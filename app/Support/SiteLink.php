<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

class SiteLink
{
    /**
     * Resolve a link stored in the back-office: either a page key
     * (named route, in the current locale) or an absolute URL.
     */
    public static function url(?string $target): string
    {
        if (blank($target)) {
            return '#';
        }

        if (Route::has($target)) {
            return route($target);
        }

        return $target;
    }

    /**
     * Internal pages an editor can link to.
     *
     * @return array<string, string>
     */
    public static function pageOptions(): array
    {
        return [
            'accueil' => 'Accueil',
            'a-propos' => 'À propos',
            'comment-ca-fonctionne' => 'Comment ça marche',
            'secteurs' => 'Secteurs & équipements',
            'pourquoi-sabonea' => 'Pourquoi Sabonea',
            'contact' => 'Contact',
            'expression-de-besoin' => 'Exprimer un besoin',
            'fournisseur-exemple' => 'Exemple de vitrine fournisseur',
            'devenir-fournisseur' => 'Devenir fournisseur (formulaire)',
            'politique-de-confidentialite' => 'Politique de confidentialité',
            'conditions-fournisseurs' => 'Conditions de partenariat fournisseur',
            'mentions-legales' => 'Mentions légales',
        ];
    }
}
