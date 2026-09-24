<?php

namespace App\SupplierForms;

use App\Models\EquipmentType;
use App\Models\FormOption;
use App\Models\Sector;
use App\Support\Locales;
use Illuminate\Support\Facades\Cache;

/**
 * Choice lists of the forms: each option is stored under a fixed technical key,
 * its label is translated in the visitor's language.
 */
class OptionLists
{
    public const CACHE_KEY = 'sabonea.option_lists';

    /** Shared reference list (equipment_types table). */
    public const EQUIPMENT = 'equipment_categories';

    /** Shared reference list (sectors table). */
    public const SECTORS = 'sectors';

    /**
     * Lists stored in form_options, with their back-office description.
     *
     * @var array<string, string>
     */
    public const FORM_OPTION_LISTS = [
        'contact_subject' => 'Contact : objet du message',
        'need_deadline' => 'Expression de besoin : délai souhaité',
        'company_age' => 'Fournisseur : ancienneté de l\'entreprise',
        'sales_languages' => 'Fournisseur : langues de l\'équipe commerciale',
        'supplier_type' => 'Fournisseur : fabricant ou distributeur',
        'exports' => 'Fournisseur : exporte à l\'international',
        'interest_level' => 'Fournisseur : intérêt pour la marketplace',
        'rfq_ready' => 'Fournisseur : prêt à recevoir des demandes de devis',
        'contact_role' => 'Fournisseur : fonction du contact',
        'preferred_channel' => 'Fournisseur : canal de contact préféré',
        'preferred_language' => 'Fournisseur : langue de contact préférée',
        'source' => 'Fournisseur : comment avez-vous connu Sabonea',
        'headcount' => 'Dossier fournisseur : effectif',
        'annual_revenue' => 'Dossier fournisseur : chiffre d\'affaires',
        'customization' => 'Dossier fournisseur : personnalisation',
        'production_mode' => 'Dossier fournisseur : fabrication en série ou sur commande',
        'certifications' => 'Dossier fournisseur : certifications',
        'warranty_duration' => 'Dossier fournisseur : durée de garantie',
        'after_sales' => 'Dossier fournisseur : service après-vente',
        'spare_parts' => 'Dossier fournisseur : pièces détachées',
        'remote_support' => 'Dossier fournisseur : assistance à distance',
        'service_network' => 'Dossier fournisseur : réseau de maintenance à l\'étranger',
        'yes_no' => 'Dossier fournisseur : Oui / Non',
        'lead_time' => 'Dossier fournisseur : délai de fabrication',
        'production_ownership' => 'Dossier fournisseur : production interne ou sous-traitée',
        'pricing_model' => 'Dossier fournisseur : structure des prix',
        'currency' => 'Dossier fournisseur : devise de facturation',
        'payment_methods' => 'Dossier fournisseur : moyens de paiement',
        'payment_terms' => 'Dossier fournisseur : délai de paiement',
        'volume_discounts' => 'Dossier fournisseur : remises selon le volume',
        'quote_response_time' => 'Dossier fournisseur : délai de réponse aux devis',
        'incoterms' => 'Dossier fournisseur : Incoterms',
        'transport_responsibility' => 'Dossier fournisseur : prise en charge du transport',
        'collaboration_type' => 'Dossier fournisseur : forme de collaboration',
        'price_display' => 'Dossier fournisseur : affichage des prix',
    ];

    /**
     * Active options of a list: key => label in the given language.
     *
     * @return array<string, string>
     */
    public static function options(string $list, ?string $locale = null): array
    {
        return collect(self::all()[$list] ?? [])
            ->filter(fn (array $option): bool => $option['active'])
            ->map(fn (array $option): string => self::translate($option['label'], $locale))
            ->all();
    }

    /**
     * Label of a stored key (inactive options included), or the key itself when unknown.
     */
    public static function label(string $list, ?string $key, ?string $locale = null): ?string
    {
        if (blank($key)) {
            return null;
        }

        $option = self::all()[$list][$key] ?? null;

        return $option ? self::translate($option['label'], $locale) : $key;
    }

    /**
     * @param  array<int, string>|null  $keys
     */
    public static function labels(string $list, ?array $keys, ?string $locale = null): string
    {
        return collect($keys ?? [])->map(fn (string $key): ?string => self::label($list, $key, $locale))->filter()->implode(', ');
    }

    /**
     * Keys of the options asking for a free text ("Other", "please specify").
     *
     * @return array<int, string>
     */
    public static function otherKeys(string $list): array
    {
        return array_keys(array_filter(self::all()[$list] ?? [], fn (array $option): bool => $option['other']));
    }

    /**
     * Keys of the options that cannot be combined with any other one ("None").
     *
     * @return array<int, string>
     */
    public static function exclusiveKeys(string $list): array
    {
        return array_keys(array_filter(self::all()[$list] ?? [], fn (array $option): bool => $option['exclusive']));
    }

    /**
     * @return array<int, string>
     */
    public static function activeKeys(string $list): array
    {
        return array_keys(self::options($list));
    }

    /**
     * Plain arrays only: the cache store refuses to unserialize objects.
     *
     * @return array<string, array<string, array{label: array<string, string>, active: bool, other: bool, exclusive: bool}>>
     */
    public static function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function (): array {
            $lists = [];

            foreach (FormOption::query()->whereNotNull('key')->orderBy('sort')->get() as $option) {
                $lists[$option->field][$option->key] = [
                    'label' => $option->label ?? [],
                    'active' => $option->is_active,
                    'other' => $option->is_other,
                    'exclusive' => $option->is_exclusive,
                ];
            }

            foreach ([self::EQUIPMENT => EquipmentType::class, self::SECTORS => Sector::class] as $list => $model) {
                foreach ($model::query()->whereNotNull('key')->orderBy('sort')->get() as $item) {
                    $lists[$list][$item->key] = [
                        'label' => array_filter($item->form_label ?? []) + array_filter($item->name ?? []),
                        'active' => $item->is_active,
                        'other' => $item->is_other,
                        'exclusive' => false,
                    ];
                }
            }

            return $lists;
        });
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * @param  array<string, string>  $label
     */
    private static function translate(array $label, ?string $locale): string
    {
        $locale ??= app()->getLocale();

        return filled($label[$locale] ?? null) ? $label[$locale] : ($label[Locales::reference()] ?? '');
    }
}
