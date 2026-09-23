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
                Translatable::tabs(fn (string $locale, bool $isReference): array => [
                    TextInput::make("name.{$locale}")->label('Nom')->required($isReference),
                    TextInput::make("short_name.{$locale}")->label('Nom court (cartes de l\'accueil)')
                        ->helperText('Facultatif : remplace le nom sur la page d\'accueil.'),
                ]),
                Grid::make(2)->columnSpanFull()->schema([
                    FileUpload::make('image')->label('Image')->image()->disk('public')->directory('uploads')->maxSize(4096)->required()->columnSpanFull(),
                    TextInput::make('icon')->label('Icône')->placeholder('fa fa-plane')
                        ->helperText('Classe Font Awesome 5, par exemple « fa fa-plane ».')->required(),
                    Toggle::make('show_on_home')->label('Afficher sur la page d\'accueil'),
                    Toggle::make('is_active')->label('Actif (visible sur le site et dans le formulaire)')->default(true),
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
                TextColumn::make('icon')->label('Icône')->color('gray')->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('show_on_home')->label('Accueil'),
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
            'index' => ManageSectors::route('/'),
        ];
    }
}
