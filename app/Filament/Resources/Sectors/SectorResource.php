<?php

namespace App\Filament\Resources\Sectors;

use App\Filament\Resources\Sectors\Pages\ManageSectors;
use App\Filament\Support\Translatable;
use App\Models\Sector;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class SectorResource extends Resource
{
    protected static ?string $model = Sector::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'secteur';

    protected static ?string $pluralModelLabel = 'secteurs';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Clé technique')
                    ->helperText('Code fixe enregistré dans les réponses et les exports (ex. SEC_AIRPORT). Non modifiable après création.')
                    ->required()
                    ->regex('/^[A-Z0-9_]+$/')
                    ->unique(ignoreRecord: true)
                    ->disabledOn('edit'),
                Translatable::tabs(fn (string $locale, bool $isReference): array => [
                    TextInput::make("name.{$locale}")->label('Nom sur le site')->required($isReference),
                    TextInput::make("short_name.{$locale}")->label('Nom court (cartes de l\'accueil)')
                        ->helperText('Facultatif : remplace le nom sur la page d\'accueil.'),
                    TextInput::make("form_label.{$locale}")->label('Libellé dans les formulaires')
                        ->helperText('Facultatif : remplace le nom dans les formulaires acheteur et fournisseurs.'),
                ]),
                Grid::make(2)->columnSpanFull()->schema([
                    FileUpload::make('image')->label('Image (page Secteurs)')->image()->disk('public')->directory('uploads')->maxSize(4096)->columnSpanFull(),
                    TextInput::make('icon')->label('Icône')->placeholder('fa fa-plane')
                        ->helperText('Classe Font Awesome 5, par exemple « fa fa-plane ».')->required(),
                    Toggle::make('is_active')->label('Actif (proposé dans les formulaires)')->default(true),
                    Toggle::make('show_on_site')->label('Affiché sur la page Secteurs')->default(true)
                        ->helperText('Nécessite une image.'),
                    Toggle::make('show_on_home')->label('Affiché sur la page d\'accueil'),
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
                ImageColumn::make('image')->label('Image')->disk('public')->imageHeight(40),
                Translatable::column('name', 'Nom'),
                TextColumn::make('key')->label('Clé')->color('gray')->fontFamily('mono')->size('xs'),
                ToggleColumn::make('is_active')->label('Actif'),
                ToggleColumn::make('show_on_site')->label('Page Secteurs'),
                ToggleColumn::make('show_on_home')->label('Accueil'),
                Translatable::statusColumn(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->visible(fn (Sector $record): bool => blank($record->key)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSectors::route('/'),
        ];
    }
}
