<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use App\Enums\ContactMessageStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Suivi du message')
                    ->description('Le message reçu n\'est pas modifiable.')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('status')
                            ->label('Statut')
                            ->options(ContactMessageStatus::class)
                            ->required(),
                        Textarea::make('internal_notes')
                            ->label('Notes internes')
                            ->rows(5),
                    ]),
            ]);
    }
}
