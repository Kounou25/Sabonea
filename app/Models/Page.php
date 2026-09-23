<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'key', 'name', 'meta_title', 'meta_description', 'meta_keywords', 'noindex',
    'header_title', 'breadcrumb', 'header_image', 'header_image_alt',
])]
class Page extends Model
{
    use HasTranslations;

    /**
     * @var array<int, string>
     */
    protected array $translatable = [
        'meta_title', 'meta_description', 'meta_keywords', 'header_title', 'breadcrumb', 'header_image_alt',
    ];

    /**
     * @return HasMany<PageSection, $this>
     */
    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort');
    }

    /**
     * A visible section of the page, looked up by its key.
     */
    public function section(string $key): ?PageSection
    {
        return $this->sections->first(fn (PageSection $section) => $section->key === $key && $section->is_visible);
    }

    /**
     * Online languages missing a translation anywhere on the page (SEO, header, sections and their items).
     *
     * @return array<int, string>
     */
    public function missingLocalesWithContent(): array
    {
        $this->loadMissing('sections.items');

        return collect([$this, ...$this->sections, ...$this->sections->flatMap->items])
            ->flatMap(fn (Model $model): array => $model->missingLocales())
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'noindex' => 'boolean',
        ];
    }
}
