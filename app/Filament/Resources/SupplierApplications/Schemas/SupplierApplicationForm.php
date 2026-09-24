<?php

namespace App\Filament\Resources\SupplierApplications\Schemas;

use App\Enums\SupplierApplicationStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Suivi de la candidature')
                    ->description('Les réponses du fournisseur ne sont pas modifiables.')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('status')->label('Statut')->options(SupplierApplicationStatus::class)->required(),
                        Textarea::make('internal_notes')->label('Notes internes')->rows(6)
                            ->helperText('Visibles uniquement dans le back-office.'),
                    ]),
            ]);
    }
}
