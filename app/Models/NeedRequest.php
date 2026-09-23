<?php

namespace App\Models;

use App\Enums\NeedRequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'name', 'company', 'email', 'phone', 'country', 'sector_id', 'equipment_type_id', 'deadline_option_id',
    'message', 'locale', 'status', 'assigned_to', 'internal_notes', 'ip_address',
])]
class NeedRequest extends Model
{
    /**
     * @return BelongsTo<Sector, $this>
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    /**
     * @return BelongsTo<EquipmentType, $this>
     */
    public function equipmentType(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class);
    }

    /**
     * @return BelongsTo<FormOption, $this>
     */
    public function deadline(): BelongsTo
    {
        return $this->belongsTo(FormOption::class, 'deadline_option_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => NeedRequestStatus::class,
        ];
    }
}
