<?php

namespace App\Filament\Resources\Languages;

use App\Filament\Resources\Languages\Pages\ManageLanguages;
use App\Models\Language;
use App\Support\Locales;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'langue';

    protected static ?string $pluralModelLabel = 'langues';

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    /**
     * The supported languages are defined in config/sabonea.php.
     */
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label('Nom affiché')->required(),
                Toggle::make('is_active')
                    ->label('En ligne')
                    ->helperText('Une langue hors ligne n\'est plus accessible sur le site.')
                    ->disabled(fn (?Language $record): bool => $record?->code === Locales::reference()),
                Toggle::make('is_default')
                    ->label('Langue par défaut')
                    ->helperText('Utilisée quand la langue du navigateur du visiteur n\'est pas disponible.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->paginated(false)
            ->columns([
                TextColumn::make('code')->label('Code')->formatStateUsing(fn (string $state): string => strtoupper($state))->badge(),
                TextColumn::make('name')->label('Nom'),
                ToggleColumn::make('is_active')->label('En ligne')
                    ->disabled(fn (Language $record): bool => $record->code === Locales::reference()),
                IconColumn::make('is_default')->label('Par défaut')->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLanguages::route('/'),
        ];
    }
}
