<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use App\Models\ContactMessage;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Expéditeur')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')->label('Nom'),
                        TextEntry::make('email')->label('E-mail')->copyable()->url(fn (ContactMessage $record): string => "mailto:{$record->email}"),
                        TextEntry::make('phone')->label('Téléphone')->placeholder('—')->copyable(),
                        TextEntry::make('locale')->label('Langue du site')->formatStateUsing(fn (string $state): string => strtoupper($state))->badge(),
                    ]),
                Section::make('Message')
                    ->schema([
                        TextEntry::make('subject_label')->label('Objet')
                            ->state(fn (ContactMessage $record): string => $record->subject?->t('label', 'fr') ?? '—'),
                        TextEntry::make('message')->label('Message')->prose(),
                    ]),
                Section::make('Suivi')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('status')->label('Statut')->badge(),
                        TextEntry::make('created_at')->label('Reçu le')->dateTime('d/m/Y à H:i'),
                        TextEntry::make('internal_notes')->label('Notes internes')->placeholder('—')->columnSpanFull(),
                    ]),
            ]);
    }
}
