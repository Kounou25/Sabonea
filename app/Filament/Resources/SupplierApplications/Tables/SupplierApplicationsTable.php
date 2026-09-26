<?php

namespace App\Filament\Resources\SupplierApplications\Tables;

use App\Enums\SupplierApplicationStatus;
use App\Filament\Resources\SupplierApplications\Actions\ProfilePdfAction;
use App\Models\Language;
use App\Models\SupplierApplication;
use App\SupplierForms\OptionLists;
use App\Support\Countries;
use App\Support\Locales;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class SupplierApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('contact_submitted_at', 'desc')
            ->columns([
                TextColumn::make('company_name')
                    ->label('Entreprise')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->description(fn (SupplierApplication $record): string => collect([
                        $record->contact_answers['city'] ?? null,
                        Countries::name($record->country, 'fr'),
                        $record->is_test ? 'Test' : null,
                    ])->filter()->implode(' · '))
                    ->wrap(),

                TextColumn::make('contact_name')
                    ->label('Contact')
                    ->searchable(['contact_name', 'contact_email'])
                    ->formatStateUsing(fn (string $state, SupplierApplication $record): HtmlString => self::contact($state, $record->preferredLocale()))
                    ->html()
                    ->description(fn (SupplierApplication $record): ?string => OptionLists::label('contact_role', $record->contact_answers['contact_role'] ?? null, 'fr'))
                    ->wrap()
                    ->tooltip(fn (SupplierApplication $record): string => 'Langue choisie : '.(Locales::all()[$record->preferredLocale()] ?? $record->preferredLocale())),

                TextColumn::make('equipment')
                    ->label('Équipements')
                    ->state(fn (SupplierApplication $record): array => self::equipment($record))
                    ->badge()
                    ->color('gray')
                    ->limit(30)
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->tooltip(fn (SupplierApplication $record): string => implode("\n", self::equipment($record)))
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Avancement')
                    ->badge()
                    ->sortable()
                    ->description(fn (SupplierApplication $record): ?string => self::progress($record)),

                TextColumn::make('contact_submitted_at')
                    ->label('Reçue le')
                    ->dateTime('d/m/Y H:i')
                    ->color('gray')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('contact_email')->label('E-mail')->searchable()->copyable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('contact_phone')->label('Téléphone')->toggleable(isToggledHiddenByDefault: true),
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
                ViewAction::make()->iconButton()->tooltip('Ouvrir la candidature'),
                ProfilePdfAction::make()->iconButton()->tooltip('Fiche PDF'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(25)
            ->emptyStateIcon(Heroicon::OutlinedBuildingStorefront)
            ->emptyStateHeading('Aucune candidature ici')
            ->emptyStateDescription('Les réponses au formulaire « Devenir fournisseur » apparaissent dans cette liste.');
    }

    /**
     * Where the file stands and since when, under the status badge.
     */
    private static function progress(SupplierApplication $record): ?string
    {
        return match ($record->status) {
            SupplierApplicationStatus::New => 'À examiner · reçue '.$record->contact_submitted_at?->diffForHumans(),
            SupplierApplicationStatus::Approved => match (true) {
                $record->onboarding_saved_at !== null && $record->hasValidOnboardingLink() => 'En cours de saisie · '.$record->onboarding_saved_at->diffForHumans(),
                $record->hasValidOnboardingLink() => 'Lien valable jusqu\'au '.$record->onboarding_token_expires_at->format('d/m/Y'),
                default => 'Lien expiré ou désactivé',
            },
            SupplierApplicationStatus::OnboardingSubmitted => 'À examiner · reçu '.$record->onboarding_submitted_at?->diffForHumans(),
            SupplierApplicationStatus::Integrated => $record->onboarding_submitted_at ? 'Dossier reçu le '.$record->onboarding_submitted_at->format('d/m/Y') : null,
            SupplierApplicationStatus::Rejected => 'Contact du '.$record->contact_submitted_at?->format('d/m/Y'),
        };
    }

    /**
     * @return array<int, string>
     */
    private static function equipment(SupplierApplication $record): array
    {
        return array_map(
            fn (string $key): string => (string) OptionLists::label(OptionLists::EQUIPMENT, $key, 'fr'),
            (array) ($record->contact_answers['equipment_categories'] ?? []),
        );
    }

    /**
     * Name of the contact, after the flag of the language he chose (the one to write to him in).
     */
    private static function contact(string $name, string $locale): HtmlString
    {
        $flag = (new Language(['code' => $locale]))->flagUrl();

        return new HtmlString('<span style="display:inline-flex;align-items:center;gap:8px;white-space:nowrap"><img src="'.e($flag).'" alt="'.e(strtoupper($locale)).'" width="18" height="13" style="border-radius:2px;box-shadow:0 0 0 1px rgba(0,0,0,.08)">'.e($name).'</span>');
    }
}
