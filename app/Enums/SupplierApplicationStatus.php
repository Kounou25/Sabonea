<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum SupplierApplicationStatus: string implements HasColor, HasIcon, HasLabel
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

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::New => Heroicon::OutlinedSparkles,
            self::Approved => Heroicon::OutlinedPaperAirplane,
            self::OnboardingSubmitted => Heroicon::OutlinedInboxArrowDown,
            self::Integrated => Heroicon::OutlinedCheckBadge,
            self::Rejected => Heroicon::OutlinedXCircle,
        };
    }

    /**
     * Statuses waiting for an action of the team.
     *
     * @return array<int, self>
     */
    public static function toProcess(): array
    {
        return [self::New, self::OnboardingSubmitted];
    }
}
