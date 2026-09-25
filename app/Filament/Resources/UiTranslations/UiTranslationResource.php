<?php

namespace App\Filament\Resources\UiTranslations;

use App\Filament\Resources\UiTranslations\Pages\ManageUiTranslations;
use App\Filament\Support\Translatable;
use App\Models\UiTranslation;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class UiTranslationResource extends Resource
{
    protected static ?string $model = UiTranslation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;

    protected static string|UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'texte de l\'interface';

    protected static ?string $pluralModelLabel = 'textes de l\'interface';

    /**
     * @var array<string, string>
     */
    public const GROUPS = [
        'layout' => 'En-tête',
        'nav' => 'Menu et boutons',
        'footer' => 'Pied de page',
        'form' => 'Formulaires (commun)',
        'contact' => 'Formulaire de contact',
        'need' => 'Formulaire « Exprimer un besoin »',
        'supplier_form' => 'Formulaires fournisseurs : boutons et messages',
        'supplier_contact' => 'Formulaire fournisseur 1 : prise de contact',
        'supplier_onboarding' => 'Formulaire fournisseur 2 : dossier d\'intégration',
        'mail' => 'E-mails envoyés aux visiteurs et aux fournisseurs',
        'error' => 'Page d\'erreur (page introuvable)',
        'pdf' => 'Fiche fournisseur PDF : textes',
        'supplier_pdf' => 'Fiche fournisseur PDF : libellés des réponses',
    ];

    /**
     * Keys are used by the site templates: texts can be edited, keys cannot be added or removed.
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
                Translatable::tabs(fn (string $locale, bool $isReference): array => [
                    Textarea::make("text.{$locale}")->label('Texte')->rows(2)->required($isReference),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('key')
            ->defaultGroup(Group::make('group')
                ->label('Zone')
                ->getTitleFromRecordUsing(fn (UiTranslation $record): string => self::GROUPS[$record->group] ?? $record->group))
            ->columns([
                Translatable::column('text', 'Texte (français)'),
                TextColumn::make('key')->label('Clé technique')->color('gray')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Translatable::statusColumn(),
            ])
            ->filters([
                SelectFilter::make('group')->label('Zone')->options(self::GROUPS),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUiTranslations::route('/'),
        ];
    }
}
