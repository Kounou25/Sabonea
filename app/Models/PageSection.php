<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'page_id', 'key', 'name', 'fields', 'item_fields', 'eyebrow', 'title', 'subtitle', 'body', 'note',
    'image', 'image_alt', 'cta_label', 'cta_url', 'cta2_label', 'cta2_url', 'is_visible', 'sort',
])]
class PageSection extends Model
{
    use HasTranslations;

    /**
     * @var array<int, string>
     */
    protected array $translatable = [
        'eyebrow', 'title', 'subtitle', 'body', 'note', 'image_alt', 'cta_label', 'cta2_label',
    ];

    /**
     * @return BelongsTo<Page, $this>
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * @return HasMany<SectionItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(SectionItem::class)->orderBy('sort');
    }

    /**
     * Whether the page layout of this section displays the given field.
     */
    public function uses(string $field): bool
    {
        return in_array($field, $this->fields ?? [], true);
    }

    public function hasItems(): bool
    {
        return filled($this->item_fields);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'item_fields' => 'array',
            'is_visible' => 'boolean',
        ];
    }
}
