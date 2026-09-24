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
use Filament\Schemas\Components\Grid;
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

    protected static ?string $modelLabel = 'catégorie d\'équipement';

    protected static ?string $pluralModelLabel = 'catégories d\'équipements';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Clé technique')
                    ->helperText('Code fixe enregistré dans les réponses et les exports (ex. EQ_ROAD_SWEEPER). Non modifiable après création.')
                    ->required()
                    ->regex('/^[A-Z0-9_]+$/')
                    ->unique(ignoreRecord: true)
                    ->disabledOn('edit'),
                Translatable::tabs(fn (string $locale, bool $isReference): array => [
                    TextInput::make("name.{$locale}")->label('Nom')->required($isReference),
                    TextInput::make("form_label.{$locale}")->label('Libellé dans les formulaires')
                        ->helperText('Facultatif : remplace le nom dans les formulaires.'),
                ]),
                Grid::make(2)->columnSpanFull()->schema([
                    TextInput::make('icon')->label('Icône')->placeholder('fa fa-broom')
                        ->helperText('Classe Font Awesome 5, par exemple « fa fa-broom ».')->required(),
                    Toggle::make('is_active')->label('Actif (proposé dans les formulaires)')->default(true),
                    Toggle::make('show_on_site')->label('Affiché sur la page Secteurs')->default(true),
                    Toggle::make('is_other')->label('Option « Autre » (demande une précision)'),
                ]),
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
                TextColumn::make('key')->label('Clé')->color('gray')->fontFamily('mono')->size('xs'),
                ToggleColumn::make('is_active')->label('Actif'),
                ToggleColumn::make('show_on_site')->label('Page Secteurs'),
                Translatable::statusColumn(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->visible(fn (EquipmentType $record): bool => blank($record->key)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEquipmentTypes::route('/'),
        ];
    }
}
