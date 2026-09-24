<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\SupplierForms\OptionLists;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Shared reference list of equipment categories: site pages, buyer form and supplier forms.
 */
#[Fillable(['key', 'name', 'form_label', 'icon', 'is_active', 'is_other', 'show_on_site', 'sort'])]
class EquipmentType extends Model
{
    use HasTranslations;

    /**
     * @var array<int, string>
     */
    protected array $translatable = ['name', 'form_label'];

    protected static function booted(): void
    {
        static::saved(fn () => OptionLists::flush());
        static::deleted(fn () => OptionLists::flush());
    }

    /**
     * @param  Builder<self>  $query
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_active', true)->orderBy('sort');
    }

    /**
     * Equipment types displayed on the public pages.
     *
     * @param  Builder<self>  $query
     */
    #[Scope]
    protected function onSite(Builder $query): void
    {
        $query->where('is_active', true)->where('show_on_site', true)->orderBy('sort');
    }

    /**
     * Label used in the forms (form label when provided).
     */
    public function formLabel(?string $locale = null): ?string
    {
        return $this->t('form_label', $locale) ?? $this->t('name', $locale);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_other' => 'boolean',
            'show_on_site' => 'boolean',
        ];
    }
}
