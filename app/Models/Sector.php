<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'short_name', 'icon', 'image', 'show_on_home', 'is_active', 'sort'])]
class Sector extends Model
{
    use HasTranslations;

    /**
     * @var array<int, string>
     */
    protected array $translatable = ['name', 'short_name'];

    /**
     * @param  Builder<self>  $query
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_active', true)->orderBy('sort');
    }

    /**
     * Label used on the home page cards (short name when provided).
     */
    public function shortLabel(): ?string
    {
        return $this->t('short_name') ?? $this->t('name');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'show_on_home' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
