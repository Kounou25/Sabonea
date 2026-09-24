<?php

namespace App\Filament\Resources\SupplierApplications\Tables;

use App\Enums\SupplierApplicationStatus;
use App\Filament\Resources\SupplierApplications\Actions\ProfilePdfAction;
use App\Models\SupplierApplication;
use App\SupplierForms\OptionLists;
use App\Support\Countries;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SupplierApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('contact_submitted_at', 'desc')
            ->columns([
                TextColumn::make('contact_submitted_at')->label('Reçu le')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('status')->label('Statut')->badge()->sortable(),
                TextColumn::make('company_name')->label('Entreprise')->searchable()->weight('bold')
                    ->description(fn (SupplierApplication $record): ?string => $record->contact_name),
                TextColumn::make('country')->label('Pays')
                    ->formatStateUsing(fn (?string $state): ?string => Countries::name($state, 'fr')),
                TextColumn::make('equipment')->label('Équipements')->wrap()->limit(80)->toggleable()
                    ->state(fn (SupplierApplication $record): string => OptionLists::labels(OptionLists::EQUIPMENT, $record->contact_answers['equipment_categories'] ?? [], 'fr')),
                TextColumn::make('onboarding')->label('Dossier (formulaire 2)')->badge()
                    ->state(fn (SupplierApplication $record): string => match (true) {
                        $record->onboarding_submitted_at !== null => 'Reçu',
                        $record->onboarding_saved_at !== null => 'En cours de saisie',
                        $record->hasValidOnboardingLink() => 'Lien actif',
                        default => '—',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Reçu' => 'success',
                        'En cours de saisie' => 'info',
                        'Lien actif' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('contact_email')->label('E-mail')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('is_test')->label('Test')->badge()->color('gray')
                    ->formatStateUsing(fn (bool $state): ?string => $state ? 'Test' : null)
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Statut')->options(SupplierApplicationStatus::class),
                SelectFilter::make('equipment_category')->label('Catégorie d\'équipement')
                    ->options(fn (): array => OptionLists::options(OptionLists::EQUIPMENT, 'fr'))
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $query, string $key): Builder => $query->whereLike('contact_answers->equipment_categories', "%\"{$key}\"%"),
                    )),
                SelectFilter::make('sector')->label('Secteur')
                    ->options(fn (): array => OptionLists::options(OptionLists::SECTORS, 'fr'))
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $query, string $key): Builder => $query->whereLike('contact_answers->sectors', "%\"{$key}\"%"),
                    )),
                SelectFilter::make('country')->label('Pays')->searchable()
                    ->options(fn (): array => Countries::options('fr')),
                TernaryFilter::make('is_test')->label('Réponses de test')
                    ->placeholder('Toutes')
                    ->trueLabel('Tests uniquement')
                    ->falseLabel('Hors tests')
                    ->default(false),
            ])
            ->recordActions([
                ViewAction::make(),
                ProfilePdfAction::make()->iconButton()->tooltip('Fiche PDF'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
