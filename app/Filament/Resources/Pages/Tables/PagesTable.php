<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Filament\Support\Translatable;
use App\Models\Page;
use App\Support\Locales;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id')
            ->paginated(false)
            ->columns([
                TextColumn::make('name')->label('Page')->weight('bold'),
                TextColumn::make('url')->label('Adresse')
                    ->state(fn (Page $record): string => '/'.Locales::reference().($record->key === 'accueil' ? '' : '/'.$record->key))
                    ->color('gray'),
                TextColumn::make('sections_count')->label('Sections')->counts('sections'),
                Translatable::statusColumn(fn (Page $record): array => $record->missingLocalesWithContent()),
                TextColumn::make('updated_at')->label('Modifiée le')->dateTime('d/m/Y H:i'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('preview')
                    ->label('Voir')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->color('gray')
                    ->url(fn (Page $record): string => route($record->key, ['locale' => Locales::reference()]))
                    ->openUrlInNewTab(),
            ]);
    }
}
