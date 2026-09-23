<?php

namespace App\Models;

use App\Enums\ContactMessageStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'email', 'phone', 'subject_option_id', 'message', 'locale', 'status', 'internal_notes', 'ip_address'])]
class ContactMessage extends Model
{
    /**
     * @return BelongsTo<FormOption, $this>
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(FormOption::class, 'subject_option_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ContactMessageStatus::class,
        ];
    }
}
