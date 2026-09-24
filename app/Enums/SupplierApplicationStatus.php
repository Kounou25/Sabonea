<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SupplierApplicationStatus: string implements HasColor, HasLabel
{
    /** Form 1 received, to be reviewed. */
    case New = 'new';

    /** Validated: the private link to form 2 can be sent. */
    case Approved = 'approved';

    /** Form 2 received, to be reviewed. */
    case OnboardingSubmitted = 'onboarding_submitted';

    case Integrated = 'integrated';

    case Rejected = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'Nouveau contact',
            self::Approved => 'Validé : dossier à remplir',
            self::OnboardingSubmitted => 'Dossier reçu',
            self::Integrated => 'Intégré',
            self::Rejected => 'Refusé',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'warning',
            self::Approved => 'info',
            self::OnboardingSubmitted => 'primary',
            self::Integrated => 'success',
            self::Rejected => 'gray',
        };
    }
}
