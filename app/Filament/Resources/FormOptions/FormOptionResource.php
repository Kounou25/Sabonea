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
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;
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
                Grid::make(2)->columnSpanFull()->schema([
                    Select::make('field')->label('Liste')->options(FormOption::fields())->required()->searchable()
                        ->disabledOn('edit'),
                    TextInput::make('key')
                        ->label('Clé technique')
                        ->helperText('Code fixe enregistré dans les réponses et les exports. Non modifiable après création.')
                        ->required()
                        ->regex('/^[A-Z0-9_]+$/')
                        ->unique(ignoreRecord: true, modifyRuleUsing: fn (Unique $rule, Get $get): Unique => $rule->where('field', $get('field')))
                        ->disabledOn('edit'),
                ]),
                Translatable::tabs(fn (string $locale, bool $isReference): array => [
                    TextInput::make("label.{$locale}")->label('Libellé')->required($isReference),
                ]),
                Grid::make(3)->columnSpanFull()->schema([
                    Toggle::make('is_active')->label('Proposée dans le formulaire')->default(true),
                    Toggle::make('is_other')->label('Demande une précision (« Autre »)'),
                    Toggle::make('is_exclusive')->label('Exclusive (« Aucune »)'),
                ]),
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
                TextColumn::make('key')->label('Clé')->color('gray')->fontFamily('mono')->size('xs'),
                ToggleColumn::make('is_active')->label('Proposée'),
                Translatable::statusColumn(),
            ])
            ->filters([
                SelectFilter::make('field')->label('Liste')->options(FormOption::fields())->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->visible(fn (FormOption $record): bool => blank($record->key)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageFormOptions::route('/'),
        ];
    }
}
