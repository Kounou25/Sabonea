<?php

namespace App\Filament\Resources\Pages\RelationManagers;

use App\Filament\Resources\Pages\Schemas\PageSectionForm;
use App\Filament\Support\Translatable;
use App\Models\PageSection;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sections';

    protected static ?string $title = 'Sections de la page';

    protected static ?string $modelLabel = 'section';

    protected static ?string $pluralModelLabel = 'sections';

    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Sections follow the page layout: they can be edited or hidden, not created or deleted.
     */
    public function form(Schema $schema): Schema
    {
        return $schema->components(fn (?PageSection $record): array => PageSectionForm::components($record));
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('sort')
            ->paginated(false)
            ->modifyQueryUsing(fn ($query) => $query->with('items'))
            ->columns([
                TextColumn::make('name')->label('Section')->weight('bold'),
                TextColumn::make('preview')->label('Aperçu')
                    ->state(fn (PageSection $record): ?string => $record->t('title', 'fr') ?? $record->t('eyebrow', 'fr') ?? $record->t('subtitle', 'fr'))
                    ->limit(70)
                    ->color('gray'),
                TextColumn::make('items_count')->label('Éléments')->counts('items')->placeholder('—'),
                ToggleColumn::make('is_visible')->label('Visible'),
                Translatable::statusColumn(fn (PageSection $record): array => collect([$record, ...$record->items])
                    ->flatMap(fn ($model): array => $model->missingLocales())
                    ->unique()
                    ->values()
                    ->all()),
            ])
            ->recordActions([
                EditAction::make()
                    ->slideOver()
                    ->modalWidth(Width::FourExtraLarge),
            ]);
    }
}
