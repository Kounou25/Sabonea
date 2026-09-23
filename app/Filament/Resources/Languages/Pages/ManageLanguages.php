<?php

namespace App\Filament\Resources\Languages\Pages;

use App\Filament\Resources\Languages\LanguageResource;
use Filament\Resources\Pages\ManageRecords;

class ManageLanguages extends ManageRecords
{
    protected static string $resource = LanguageResource::class;

    public function getSubheading(): string
    {
        return 'Le français est la langue de référence : ses textes sont obligatoires et affichés à la place des traductions manquantes.';
    }
}
