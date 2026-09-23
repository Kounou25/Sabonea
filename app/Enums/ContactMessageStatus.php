<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ContactMessageStatus: string implements HasColor, HasLabel
{
    case New = 'new';
    case Read = 'read';
    case Archived = 'archived';

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'Nouveau',
            self::Read => 'Lu',
            self::Archived => 'Archivé',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'warning',
            self::Read => 'success',
            self::Archived => 'gray',
        };
    }
}
