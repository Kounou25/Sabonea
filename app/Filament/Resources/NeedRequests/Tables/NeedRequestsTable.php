<?php

namespace App\Filament\Resources\NeedRequests\Tables;

use App\Enums\NeedRequestStatus;
use App\Models\NeedRequest;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NeedRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->with(['sector', 'equipmentType', 'assignee']))
            ->columns([
                TextColumn::make('created_at')->label('Reçue le')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('status')->label('Statut')->badge()->sortable(),
                TextColumn::make('name')->label('Nom')->searchable()
                    ->description(fn (NeedRequest $record): ?string => $record->company),
                TextColumn::make('email')->label('E-mail')->searchable()->toggleable(),
                TextColumn::make('sector_label')->label('Secteur')
                    ->state(fn (NeedRequest $record): string => $record->sector?->t('name', 'fr') ?? 'Autre'),
                TextColumn::make('equipment_label')->label('Équipement')->wrap()
                    ->state(fn (NeedRequest $record): string => $record->equipmentType?->t('name', 'fr') ?? 'Autre'),
                TextColumn::make('country')->label('Pays')->searchable()->toggleable(),
                TextColumn::make('locale')->label('Langue')->formatStateUsing(fn (string $state): string => strtoupper($state))->toggleable(),
                TextColumn::make('assignee.name')->label('Suivie par')->placeholder('—')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Statut')->options(NeedRequestStatus::class),
                SelectFilter::make('sector_id')->label('Secteur')->relationship('sector', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record): string => $record->t('name', 'fr')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->label('Traiter'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
