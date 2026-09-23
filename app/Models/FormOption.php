<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['field', 'label', 'is_active', 'sort'])]
class FormOption extends Model
{
    use HasTranslations;

    public const CONTACT_SUBJECT = 'contact_subject';

    public const NEED_DEADLINE = 'need_deadline';

    /**
     * @var array<int, string>
     */
    protected array $translatable = ['label'];

    /**
     * @return array<string, string>
     */
    public static function fields(): array
    {
        return [
            self::CONTACT_SUBJECT => 'Contact : objet du message',
            self::NEED_DEADLINE => 'Expression de besoin : délai souhaité',
        ];
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
            'is_active' => 'boolean',
        ];
    }
}
