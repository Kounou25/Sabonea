<?php

namespace App\Filament\Resources\NeedRequests\Schemas;

use App\Enums\NeedRequestStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NeedRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Suivi de la demande')
                    ->description('Les informations saisies par l\'acheteur ne sont pas modifiables.')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('status')
                            ->label('Statut')
                            ->options(NeedRequestStatus::class)
                            ->required(),
                        Select::make('assigned_to')
                            ->label('Suivie par')
                            ->relationship('assignee', 'name')
                            ->searchable()
                            ->preload(),
                        Textarea::make('internal_notes')
                            ->label('Notes internes')
                            ->helperText('Visibles uniquement dans le back-office : fournisseurs contactés, échanges, prochaines étapes...')
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
