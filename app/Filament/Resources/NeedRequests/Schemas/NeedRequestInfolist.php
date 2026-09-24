<?php

namespace App\Filament\Resources\NeedRequests\Schemas;

use App\Models\NeedRequest;
use App\Support\Countries;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NeedRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Acheteur')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')->label('Nom'),
                        TextEntry::make('company')->label('Société / Organisation')->placeholder('—'),
                        TextEntry::make('email')->label('E-mail')->copyable()->url(fn (NeedRequest $record): string => "mailto:{$record->email}"),
                        TextEntry::make('phone')->label('Téléphone')->placeholder('—')->copyable(),
                        TextEntry::make('country')->label('Pays')->placeholder('—')->formatStateUsing(fn (?string $state): ?string => Countries::name($state, 'fr')),
                        TextEntry::make('locale')->label('Langue du site')->formatStateUsing(fn (string $state): string => strtoupper($state))->badge(),
                    ]),
                Section::make('Besoin')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('sector_label')->label('Secteur')
                            ->state(fn (NeedRequest $record): string => $record->sector?->t('name', 'fr') ?? 'Autre / non précisé'),
                        TextEntry::make('equipment_label')->label('Type d\'équipement')
                            ->state(fn (NeedRequest $record): string => $record->equipmentType?->t('name', 'fr') ?? 'Autre / non précisé'),
                        TextEntry::make('deadline_label')->label('Délai souhaité')
                            ->state(fn (NeedRequest $record): string => $record->deadline?->t('label', 'fr') ?? '—'),
                        TextEntry::make('message')->label('Précisions')->placeholder('—')->columnSpanFull()->prose(),
                    ]),
                Section::make('Suivi')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('status')->label('Statut')->badge(),
                        TextEntry::make('assignee.name')->label('Suivie par')->placeholder('Personne'),
                        TextEntry::make('created_at')->label('Reçue le')->dateTime('d/m/Y à H:i'),
                        TextEntry::make('internal_notes')->label('Notes internes')->placeholder('—')->columnSpanFull(),
                    ]),
            ]);
    }
}
