<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum NeedRequestStatus: string implements HasColor, HasLabel
{
    case New = 'new';
    case InReview = 'in_review';
    case Matched = 'matched';
    case Closed = 'closed';

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'Nouveau',
            self::InReview => 'En analyse',
            self::Matched => 'Mis en relation',
            self::Closed => 'Clos',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'warning',
            self::InReview => 'info',
            self::Matched => 'success',
            self::Closed => 'gray',
        };
    }
}
