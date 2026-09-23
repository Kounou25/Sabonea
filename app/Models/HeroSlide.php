<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'eyebrow', 'title', 'text', 'image', 'image_alt', 'cta_label', 'cta_url', 'cta2_label', 'cta2_url', 'is_active', 'sort',
])]
class HeroSlide extends Model
{
    use HasTranslations;

    /**
     * @var array<int, string>
     */
    protected array $translatable = ['eyebrow', 'title', 'text', 'image_alt', 'cta_label', 'cta2_label'];

    /**
     * @param  Builder<self>  $query
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_active', true)->orderBy('sort');
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
