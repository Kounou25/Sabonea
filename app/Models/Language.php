<?php

namespace App\Models;

use App\Support\Locales;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'name', 'is_active', 'is_default', 'sort'])]
class Language extends Model
{
    protected static function booted(): void
    {
        // Only one default language.
        static::saved(function (Language $language): void {
            if ($language->is_default) {
                static::query()->whereKeyNot($language->getKey())->where('is_default', true)->update(['is_default' => false]);
            }
        });
        static::saved(fn () => Locales::flush());
        static::deleted(fn () => Locales::flush());
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }
}
