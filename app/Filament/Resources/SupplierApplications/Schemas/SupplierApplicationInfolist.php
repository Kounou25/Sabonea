<?php

namespace App\Filament\Resources\SupplierApplications\Schemas;

use App\Models\SupplierApplication;
use App\SupplierForms\FieldType;
use App\SupplierForms\Question;
use App\SupplierForms\SupplierContactForm;
use App\SupplierForms\SupplierForm;
use App\SupplierForms\SupplierOnboardingForm;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Number;

/**
 * Supplier answers shown section by section, with French labels, from the form definitions.
 */
class SupplierApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('application')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Suivi')->key('suivi')->schema([
                            Section::make()->columns(3)->schema([
                                TextEntry::make('status')->label('Statut')->badge(),
                                TextEntry::make('contact_submitted_at')->label('Formulaire 1 reçu le')->dateTime('d/m/Y à H:i'),
                                TextEntry::make('is_test')->label('Réponse de test')
                                    ->formatStateUsing(fn (bool $state): string => $state ? 'Oui : exclue des exports' : 'Non'),
                                TextEntry::make('onboarding_link')->label('Lien du formulaire 2')
                                    ->state(fn (SupplierApplication $record): string => match (true) {
                                        $record->hasValidOnboardingLink() => 'Actif jusqu\'au '.$record->onboarding_token_expires_at->format('d/m/Y'),
                                        filled($record->onboarding_token) => 'Expiré le '.$record->onboarding_token_expires_at?->format('d/m/Y'),
                                        default => 'Aucun lien actif',
                                    }),
                                TextEntry::make('onboarding_saved_at')->label('Dernière saisie du dossier')->dateTime('d/m/Y à H:i')->placeholder('—'),
                                TextEntry::make('onboarding_submitted_at')->label('Dossier envoyé le')->dateTime('d/m/Y à H:i')->placeholder('—'),
                                TextEntry::make('internal_notes')->label('Notes internes')->placeholder('—')->columnSpanFull(),
                            ]),
                        ]),
                        Tab::make('Prise de contact (formulaire 1)')->key('formulaire-1')
                            ->schema(self::answerSections(new SupplierContactForm, 'contact_answers')),
                        Tab::make('Dossier d\'intégration (formulaire 2)')->key('formulaire-2')
                            ->visible(fn (SupplierApplication $record): bool => filled($record->onboarding_answers))
                            ->badge(fn (SupplierApplication $record): ?string => $record->onboarding_submitted_at === null ? 'brouillon' : null)
                            ->schema(self::answerSections(new SupplierOnboardingForm, 'onboarding_answers')),
                    ]),
            ]);
    }

    /**
     * @return array<int, Section>
     */
    private static function answerSections(SupplierForm $definition, string $column): array
    {
        return array_map(
            fn ($section): Section => Section::make($definition->sectionTitle($section, 'fr'))
                ->columns(2)
                ->collapsible()
                ->schema(array_map(fn (Question $question): TextEntry => self::answerEntry($definition, $question, $column), $section->questions)),
            $definition->sections(),
        );
    }

    private static function answerEntry(SupplierForm $definition, Question $question, string $column): TextEntry
    {
        $entry = TextEntry::make("{$column}_{$question->key}")
            ->label($definition->plainLabel($question, 'fr'))
            ->placeholder('—')
            ->columnSpan($question->wide ? 2 : 1);

        if ($question->type === FieldType::Files) {
            return $entry->state(fn (SupplierApplication $record): ?HtmlString => self::documentLinks($record, $question->key, $record->{$column} ?? []));
        }

        return $entry->state(fn (SupplierApplication $record): ?string => $definition->displayValue($question, $record->{$column} ?? [], 'fr'));
    }

    /**
     * @param  array<string, mixed>  $answers
     */
    private static function documentLinks(SupplierApplication $record, string $field, array $answers): ?HtmlString
    {
        $links = collect($answers[$field] ?? [])->map(fn (array $file, int $index): string => sprintf(
            '<a href="%s" class="text-primary-600 underline">%s</a> <span class="text-gray-500">(%s)</span>',
            e(route('supplier-documents.download', ['supplierApplication' => $record, 'field' => $field, 'index' => $index])),
            e($file['name']),
            e(Number::fileSize($file['size'] ?? 0, precision: 1)),
        ));

        return $links->isEmpty() ? null : new HtmlString($links->implode('<br>'));
    }
}
