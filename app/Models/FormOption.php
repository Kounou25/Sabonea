<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\SupplierForms\OptionLists;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Option of a choice list. The technical key is what the answers store; the label is translated.
 */
#[Fillable(['field', 'key', 'label', 'is_other', 'is_exclusive', 'is_active', 'sort'])]
class FormOption extends Model
{
    use HasTranslations;

    public const CONTACT_SUBJECT = 'contact_subject';

    public const NEED_DEADLINE = 'need_deadline';

    /**
     * @var array<int, string>
     */
    protected array $translatable = ['label'];

    protected static function booted(): void
    {
        static::saved(fn () => OptionLists::flush());
        static::deleted(fn () => OptionLists::flush());
    }

    /**
     * @return array<string, string>
     */
    public static function fields(): array
    {
        return OptionLists::FORM_OPTION_LISTS;
    }

    /**
     * @param  Builder<self>  $query
     */
    #[Scope]
    protected function published(Builder $query, string $field): void
    {
        $query->where('field', $field)->where('is_active', true)->orderBy('sort');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_other' => 'boolean',
            'is_exclusive' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
