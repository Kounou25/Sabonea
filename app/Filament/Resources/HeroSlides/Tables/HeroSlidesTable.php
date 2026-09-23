<?php

namespace App\Filament\Resources\HeroSlides\Tables;

use App\Filament\Support\Translatable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class HeroSlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                ImageColumn::make('image')->label('Image')->disk('public')->imageHeight(50),
                Translatable::column('title', 'Titre'),
                ToggleColumn::make('is_active')->label('Affiché'),
                Translatable::statusColumn(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
