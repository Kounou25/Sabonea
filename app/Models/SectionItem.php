<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['page_section_id', 'title', 'text', 'icon', 'image', 'variant', 'sort'])]
class SectionItem extends Model
{
    use HasTranslations;

    /**
     * @var array<int, string>
     */
    protected array $translatable = ['title', 'text'];

    /**
     * @return BelongsTo<PageSection, $this>
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(PageSection::class, 'page_section_id');
    }
}
