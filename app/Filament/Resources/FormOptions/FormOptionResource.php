<?php

namespace App\Filament\Resources\FormOptions;

use App\Filament\Resources\FormOptions\Pages\ManageFormOptions;
use App\Filament\Support\Translatable;
use App\Models\FormOption;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use UnitEnum;

class FormOptionResource extends Resource
{
    protected static ?string $model = FormOption::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static string|UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'option de formulaire';

    protected static ?string $pluralModelLabel = 'options de formulaires';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('field')->label('Liste')->options(FormOption::fields())->required(),
                Translatable::tabs(fn (string $locale, bool $isReference): array => [
                    TextInput::make("label.{$locale}")->label('Libellé')->required($isReference),
                ]),
                Toggle::make('is_active')->label('Proposée dans le formulaire')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->defaultGroup(Group::make('field')
                ->label('Liste')
                ->getTitleFromRecordUsing(fn (FormOption $record): string => FormOption::fields()[$record->field] ?? $record->field))
            ->columns([
                Translatable::column('label', 'Libellé'),
                ToggleColumn::make('is_active')->label('Proposée'),
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
            'index' => ManageFormOptions::route('/'),
        ];
    }
}
