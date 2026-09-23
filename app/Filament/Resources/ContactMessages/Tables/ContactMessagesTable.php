<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Enums\ContactMessageStatus;
use App\Models\ContactMessage;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->with('subject'))
            ->columns([
                TextColumn::make('created_at')->label('Reçu le')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('status')->label('Statut')->badge()->sortable(),
                TextColumn::make('name')->label('Nom')->searchable(),
                TextColumn::make('email')->label('E-mail')->searchable(),
                TextColumn::make('subject_label')->label('Objet')->wrap()
                    ->state(fn (ContactMessage $record): string => $record->subject?->t('label', 'fr') ?? '—'),
                TextColumn::make('message')->label('Message')->limit(60)->toggleable(),
                TextColumn::make('locale')->label('Langue')->formatStateUsing(fn (string $state): string => strtoupper($state))->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Statut')->options(ContactMessageStatus::class),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
