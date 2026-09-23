<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\Ui;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['group', 'key', 'text'])]
class UiTranslation extends Model
{
    use HasTranslations;

    /**
     * @var array<int, string>
     */
    protected array $translatable = ['text'];

    protected static function booted(): void
    {
        static::saved(fn () => Ui::flush());
        static::deleted(fn () => Ui::flush());
    }
}
