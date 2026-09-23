<?php

namespace App\Filament\Resources\EquipmentTypes;

use App\Filament\Resources\EquipmentTypes\Pages\ManageEquipmentTypes;
use App\Filament\Support\Translatable;
use App\Models\EquipmentType;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class EquipmentTypeResource extends Resource
{
    protected static ?string $model = EquipmentType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'type d\'équipement';

    protected static ?string $pluralModelLabel = 'types d\'équipements';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Translatable::tabs(fn (string $locale, bool $isReference): array => [
                    TextInput::make("name.{$locale}")->label('Nom')->required($isReference),
                ]),
                TextInput::make('icon')->label('Icône')->placeholder('fa fa-broom')
                    ->helperText('Classe Font Awesome 5, par exemple « fa fa-broom ».')->required(),
                Toggle::make('is_active')->label('Actif (visible sur le site et dans le formulaire)')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('icon')->label('Icône')->color('gray'),
                Translatable::column('name', 'Nom'),
                ToggleColumn::make('is_active')->label('Actif'),
                Translatable::statusColumn(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEquipmentTypes::route('/'),
        ];
    }
}
