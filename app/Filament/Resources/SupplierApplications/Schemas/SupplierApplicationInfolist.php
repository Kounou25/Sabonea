<?php

namespace App\Filament\Resources\SupplierApplications\Schemas;

use App\Models\Language;
use App\Models\SupplierApplication;
use App\SupplierForms\FieldType;
use App\SupplierForms\Question;
use App\SupplierForms\SupplierContactForm;
use App\SupplierForms\SupplierForm;
use App\SupplierForms\SupplierOnboardingForm;
use App\Support\Countries;
use App\Support\Ui;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Number;

/**
 * Supplier file: an overview first (company, activity, buying conditions, contact, follow-up),
 * then every answer of the two forms, section by section, with short French labels.
 */
class SupplierApplicationInfolist
{
    /**
     * Buying conditions shown in the overview (form 2).
     *
     * @var array<int, string>
     */
    private const KEY_POINTS = ['lead_time', 'warranty_duration', 'after_sales', 'spare_parts', 'payment_terms', 'quote_response_time', 'incoterms', 'customization'];

    /**
     * @var array<string, Heroicon>
     */
    private const SECTION_ICONS = [
        'identity' => Heroicon::OutlinedBuildingOffice2,
        'products' => Heroicon::OutlinedCube,
        'markets' => Heroicon::OutlinedGlobeEuropeAfrica,
        'interest' => Heroicon::OutlinedSparkles,
        'contact' => Heroicon::OutlinedUserCircle,
        'certifications' => Heroicon::OutlinedShieldCheck,
        'after_sales' => Heroicon::OutlinedWrenchScrewdriver,
        'production' => Heroicon::OutlinedCog6Tooth,
        'pricing' => Heroicon::OutlinedBanknotes,
        'logistics' => Heroicon::OutlinedTruck,
        'documents' => Heroicon::OutlinedPaperClip,
        'collaboration' => Heroicon::OutlinedUserGroup,
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('application')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Synthèse')->key('suivi')->icon(Heroicon::OutlinedRectangleGroup)->schema([
                            Grid::make(['default' => 1, 'lg' => 3])->schema([
                                Group::make([self::companySection(), self::activitySection(), self::keyPointsSection()])
                                    ->columnSpan(['lg' => 2]),
                                Group::make([self::contactSection(), self::followUpSection(), self::notesSection(), self::documentsSection()])
                                    ->columnSpan(['lg' => 1]),
                            ]),
                        ]),
                        Tab::make('Prise de contact (formulaire 1)')->key('formulaire-1')
                            ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                            ->schema(self::answerSections(new SupplierContactForm, 'contact_answers')),
                        Tab::make('Dossier d\'intégration (formulaire 2)')->key('formulaire-2')
                            ->icon(Heroicon::OutlinedFolderOpen)
                            ->visible(fn (SupplierApplication $record): bool => filled($record->onboarding_answers))
                            ->badge(fn (SupplierApplication $record): ?string => $record->onboarding_submitted_at === null ? 'brouillon' : null)
                            ->schema(self::answerSections(new SupplierOnboardingForm, 'onboarding_answers')),
                    ]),
            ]);
    }

    // ------------------------------------------------------------------ Overview

    private static function companySection(): Section
    {
        return Section::make('Entreprise')
            ->icon(Heroicon::OutlinedBuildingOffice2)
            ->columns(['default' => 1, 'sm' => 2, 'xl' => 3])
            ->schema([
                self::overviewEntry('legal_name', 'Raison sociale')
                    ->state(fn (SupplierApplication $record): string => self::answers($record)['legal_name'] ?? null ?: $record->company_name)
                    ->weight(FontWeight::SemiBold),
                self::overviewEntry('location', 'Localisation')
                    ->state(fn (SupplierApplication $record): ?string => collect([self::answers($record)['city'] ?? null, Countries::name(self::answers($record)['hq_country'] ?? $record->country, 'fr')])->filter()->implode(', ') ?: null)
                    ->icon(Heroicon::OutlinedMapPin),
                self::overviewEntry('supplier_type')->badge()->color('primary'),
                self::overviewEntry('founded', 'Création')
                    ->state(fn (SupplierApplication $record): ?string => filled(self::answers($record)['founding_date'] ?? null)
                        ? (string) Carbon::parse(self::answers($record)['founding_date'])->year
                        : self::display($record, 'company_age')),
                self::overviewEntry('headcount'),
                self::overviewEntry('annual_revenue'),
                self::overviewEntry('website')
                    ->url(fn (?string $state): ?string => $state)
                    ->formatStateUsing(fn (string $state): string => self::shortUrl($state))
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->iconPosition(IconPosition::After),
                self::overviewEntry('sales_languages')->badge()->color('gray')->columnSpan(['sm' => 2]),
                self::overviewEntry('flagship_product')->columnSpanFull(),
            ]);
    }

    private static function activitySection(): Section
    {
        return Section::make('Activité')
            ->icon(Heroicon::OutlinedSquares2x2)
            ->schema([
                self::overviewEntry('equipment_categories')->badge()->color('primary'),
                self::overviewEntry('sectors')->badge()->color('success'),
                self::overviewEntry('certifications')->badge()->color('warning'),
                Grid::make(['default' => 1, 'sm' => 2])->schema([
                    self::overviewEntry('manufacturing_countries')->badge()->color('gray'),
                    self::overviewEntry('export_countries')->badge()->color('gray'),
                ]),
            ]);
    }

    private static function keyPointsSection(): Section
    {
        return Section::make('Points clés')
            ->description('Conditions déclarées dans le dossier d\'intégration.')
            ->icon(Heroicon::OutlinedClipboardDocumentCheck)
            ->columns(['default' => 1, 'sm' => 2, 'xl' => 4])
            ->visible(fn (SupplierApplication $record): bool => $record->isOnboardingSubmitted())
            ->schema(array_map(fn (string $key): TextEntry => self::overviewEntry($key)->weight(FontWeight::Medium), self::KEY_POINTS));
    }

    private static function contactSection(): Section
    {
        return Section::make('Contact')
            ->icon(Heroicon::OutlinedUserCircle)
            ->schema([
                TextEntry::make('overview_contact_name')
                    ->hiddenLabel()
                    ->state(fn (SupplierApplication $record): ?string => self::answers($record)['contact_name'] ?? $record->contact_name)
                    ->weight(FontWeight::SemiBold)
                    ->size(TextSize::Large)
                    ->belowContent(fn (SupplierApplication $record): ?string => self::display($record, 'contact_role')),
                TextEntry::make('overview_contact_email')
                    ->hiddenLabel()
                    ->state(fn (SupplierApplication $record): ?string => self::answers($record)['contact_email'] ?? $record->contact_email)
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->url(fn (?string $state): ?string => $state ? "mailto:{$state}" : null)
                    ->color('primary')
                    ->copyable()
                    ->copyMessage('E-mail copié'),
                TextEntry::make('overview_contact_phone')
                    ->hiddenLabel()
                    ->state(fn (SupplierApplication $record): ?string => self::answers($record)['contact_phone'] ?? $record->contact_phone)
                    ->icon(Heroicon::OutlinedPhone)
                    ->url(fn (?string $state): ?string => $state ? 'tel:'.preg_replace('/[^0-9+]/', '', $state) : null)
                    ->color('primary')
                    ->copyable()
                    ->copyMessage('Numéro copié'),
                Grid::make(2)->schema([
                    self::overviewEntry('preferred_channel', 'Canal préféré'),
                    TextEntry::make('overview_preferred_language')
                        ->label('Langue préférée')
                        ->state(fn (SupplierApplication $record): HtmlString => self::language($record->preferredLocale(), self::display($record, 'preferred_language')))
                        ->html(),
                ]),
            ]);
    }

    private static function followUpSection(): Section
    {
        return Section::make('Suivi')
            ->icon(Heroicon::OutlinedClock)
            ->schema([
                TextEntry::make('status')->label('Statut')->badge()->size(TextSize::Medium),
                TextEntry::make('onboarding_link')
                    ->label('Lien du formulaire 2')
                    ->state(fn (SupplierApplication $record): string => match (true) {
                        $record->isOnboardingSubmitted() => 'Dossier envoyé : lien fermé',
                        $record->hasValidOnboardingLink() => 'Actif jusqu\'au '.$record->onboarding_token_expires_at->format('d/m/Y'),
                        filled($record->onboarding_token) => 'Expiré le '.$record->onboarding_token_expires_at?->format('d/m/Y'),
                        default => 'Aucun lien actif',
                    })
                    ->color(fn (SupplierApplication $record): string => $record->hasValidOnboardingLink() && ! $record->isOnboardingSubmitted() ? 'success' : 'gray')
                    ->icon(Heroicon::OutlinedLink),
                TextEntry::make('contact_submitted_at')->label('Formulaire 1 reçu')->dateTime('d/m/Y à H:i')->icon(Heroicon::OutlinedInboxArrowDown),
                TextEntry::make('onboarding_saved_at')->label('Dernière saisie du dossier')->dateTime('d/m/Y à H:i')->placeholder('—')
                    ->visible(fn (SupplierApplication $record): bool => ! $record->isOnboardingSubmitted()),
                TextEntry::make('onboarding_submitted_at')->label('Dossier reçu')->dateTime('d/m/Y à H:i')->placeholder('—')->icon(Heroicon::OutlinedFolderOpen),
                TextEntry::make('is_test')->label('Réponse de test')
                    ->visible(fn (SupplierApplication $record): bool => $record->is_test)
                    ->formatStateUsing(fn (): string => 'Oui : exclue des exports')
                    ->badge()
                    ->color('warning'),
            ]);
    }

    private static function notesSection(): Section
    {
        return Section::make('Notes internes')
            ->icon(Heroicon::OutlinedPencilSquare)
            ->schema([
                TextEntry::make('internal_notes')
                    ->hiddenLabel()
                    ->placeholder('Aucune note. Ajoutez-en avec « Autres actions › Statut et notes ».')
                    ->formatStateUsing(fn (?string $state): ?HtmlString => $state === null ? null : new HtmlString(nl2br(e($state))))
                    ->html(),
            ]);
    }

    private static function documentsSection(): Section
    {
        return Section::make('Documents')
            ->icon(Heroicon::OutlinedPaperClip)
            ->visible(fn (SupplierApplication $record): bool => $record->isOnboardingSubmitted())
            ->schema([
                TextEntry::make('overview_documents')
                    ->hiddenLabel()
                    ->state(function (SupplierApplication $record): ?HtmlString {
                        $definition = new SupplierOnboardingForm;
                        $groups = [];

                        foreach (SupplierOnboardingForm::DOCUMENT_KEYS as $key) {
                            if ($links = self::documentLinks($record, $key, $record->onboarding_answers ?? [])) {
                                $groups[] = '<div style="margin-bottom:.75rem"><div style="font-size:.75rem;font-weight:600;opacity:.65;margin-bottom:.25rem">'
                                    .e(self::label($definition, $definition->question($key))).'</div>'.$links.'</div>';
                            }
                        }

                        return $groups === [] ? null : new HtmlString(implode('', $groups));
                    })
                    ->placeholder('Aucun document joint.')
                    ->html(),
            ]);
    }

    /**
     * An answer of the overview: form 2 when it has been sent, otherwise form 1.
     */
    private static function overviewEntry(string $key, ?string $label = null): TextEntry
    {
        [$definition, $question] = self::question($key);

        // Asked in form 2 only (headcount, certifications...): hidden until the file is received.
        $onboardingOnly = $question !== null && $key !== 'legal_name' && (new SupplierContactForm)->question($key) === null;

        return TextEntry::make("overview_{$key}")
            ->label($label ?? ($question ? self::label($definition, $question) : $key))
            ->visible(fn (SupplierApplication $record): bool => ! $onboardingOnly || $record->isOnboardingSubmitted())
            ->placeholder('—')
            ->state(fn (SupplierApplication $record): string|array|null => in_array($question?->type, [FieldType::Choices, FieldType::Countries], true)
                ? self::items($definition, $question, self::answers($record))
                : self::display($record, $key));
    }

    /**
     * @return array<string, mixed>
     */
    private static function answers(SupplierApplication $record): array
    {
        return [...($record->contact_answers ?? []), ...($record->isOnboardingSubmitted() ? ($record->onboarding_answers ?? []) : [])];
    }

    private static function display(SupplierApplication $record, string $key): ?string
    {
        [$definition, $question] = self::question($key);

        return $question ? $definition->displayValue($question, self::answers($record), 'fr') : null;
    }

    /**
     * @return array{0: SupplierForm, 1: ?Question}
     */
    private static function question(string $key): array
    {
        foreach ([new SupplierOnboardingForm, new SupplierContactForm] as $definition) {
            if ($question = $definition->question($key)) {
                return [$definition, $question];
            }
        }

        return [new SupplierContactForm, null];
    }

    // ------------------------------------------------------------------ Answers, section by section

    /**
     * @return array<int, Section>
     */
    private static function answerSections(SupplierForm $definition, string $column): array
    {
        return array_map(
            fn ($section): Section => Section::make($definition->sectionTitle($section, 'fr'))
                ->icon(self::SECTION_ICONS[$section->key] ?? Heroicon::OutlinedListBullet)
                ->columns(['default' => 1, 'md' => 2, 'xl' => 3])
                ->collapsible()
                ->compact()
                ->schema(array_map(fn (Question $question): Component => self::answerEntry($definition, $question, $column), $section->questions)),
            $definition->sections(),
        );
    }

    private static function answerEntry(SupplierForm $definition, Question $question, string $column): Component
    {
        $answers = fn (SupplierApplication $record): array => $record->{$column} ?? [];
        $label = self::label($definition, $question);
        $fullQuestion = $definition->plainLabel($question, 'fr');

        if ($question->type === FieldType::Consent) {
            return IconEntry::make("{$column}_{$question->key}")
                ->label($label)
                ->hintIcon(Heroicon::OutlinedQuestionMarkCircle, trim(strip_tags((string) $definition->consentLabel($question))))
                ->hintColor('gray')
                ->state(fn (SupplierApplication $record): bool => (bool) ($answers($record)[$question->key] ?? false))
                ->boolean();
        }

        $entry = TextEntry::make("{$column}_{$question->key}")
            ->label($label)
            ->placeholder('—')
            ->columnSpan(match ($question->type) {
                FieldType::Textarea, FieldType::Files => 'full',
                FieldType::Choices, FieldType::Countries => ['md' => 2],
                default => 1,
            });

        // Short label; the question asked to the supplier stays readable on hover.
        if ($label !== $fullQuestion) {
            $entry->hintIcon(Heroicon::OutlinedQuestionMarkCircle, $fullQuestion)->hintColor('gray');
        }

        return match ($question->type) {
            FieldType::Choices, FieldType::Countries => $entry
                ->state(fn (SupplierApplication $record): array => self::items($definition, $question, $answers($record)))
                ->badge()
                ->color('gray'),
            FieldType::Choice => $entry
                ->state(fn (SupplierApplication $record): ?string => $definition->displayValue($question, $answers($record), 'fr'))
                ->badge(fn (SupplierApplication $record): bool => in_array($answers($record)[$question->key] ?? null, ['YES', 'NO'], true))
                ->color(fn (SupplierApplication $record): ?string => match ($answers($record)[$question->key] ?? null) {
                    'YES' => 'success',
                    'NO' => 'danger',
                    default => null,
                }),
            FieldType::Date => $entry
                ->state(fn (SupplierApplication $record): ?string => filled($answers($record)[$question->key] ?? null) ? Carbon::parse($answers($record)[$question->key])->format('d/m/Y') : null),
            FieldType::Url => $entry
                ->state(fn (SupplierApplication $record): ?string => $answers($record)[$question->key] ?? null)
                ->url(fn (?string $state): ?string => $state)
                ->formatStateUsing(fn (string $state): string => self::shortUrl($state))
                ->openUrlInNewTab()
                ->color('primary')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->iconPosition(IconPosition::After),
            FieldType::Email => $entry
                ->state(fn (SupplierApplication $record): ?string => $answers($record)[$question->key] ?? null)
                ->url(fn (?string $state): ?string => $state ? "mailto:{$state}" : null)
                ->color('primary')
                ->icon(Heroicon::OutlinedEnvelope)
                ->copyable(),
            FieldType::Phone => $entry
                ->state(fn (SupplierApplication $record): ?string => $answers($record)[$question->key] ?? null)
                ->icon(Heroicon::OutlinedPhone)
                ->copyable(),
            FieldType::Textarea => $entry
                ->state(fn (SupplierApplication $record): ?HtmlString => filled($answers($record)[$question->key] ?? null) ? new HtmlString(nl2br(e((string) $answers($record)[$question->key]))) : null)
                ->html(),
            FieldType::Files => $entry
                ->state(fn (SupplierApplication $record): ?HtmlString => self::documentLinks($record, $question->key, $answers($record)))
                ->html(),
            default => $entry
                ->state(fn (SupplierApplication $record): ?string => $definition->displayValue($question, $answers($record), 'fr')),
        };
    }

    /**
     * Short label (the one of the PDF profile) when there is one, otherwise the question.
     */
    private static function label(SupplierForm $definition, Question $question): string
    {
        return Ui::has("supplier_pdf.{$question->key}")
            ? Ui::get("supplier_pdf.{$question->key}", [], 'fr')
            : $definition->plainLabel($question, 'fr');
    }

    /**
     * Options or countries chosen, one label each (with the free text of "Other").
     *
     * @param  array<string, mixed>  $answers
     * @return array<int, string>
     */
    private static function items(SupplierForm $definition, Question $question, array $answers): array
    {
        $keys = array_values(array_filter((array) ($answers[$question->key] ?? []), fn ($key): bool => filled($key)));

        return array_map(
            fn (string $key): string => $question->type === FieldType::Countries
                ? (string) Countries::name($key, 'fr')
                : $definition->optionWithDetail($question, $key, $answers, 'fr'),
            $keys,
        );
    }

    /**
     * "https://www.example.com/" shown as "example.com" (the link keeps the full address).
     */
    private static function shortUrl(string $url): string
    {
        return rtrim((string) preg_replace('#^https?://(www\.)?#i', '', $url), '/');
    }

    private static function language(string $code, ?string $name): HtmlString
    {
        $flag = (new Language(['code' => $code]))->flagUrl();

        return new HtmlString('<span style="display:inline-flex;align-items:center;gap:8px"><img src="'.e($flag).'" alt="" width="20" height="15" style="border-radius:2px;box-shadow:0 0 0 1px rgba(0,0,0,.08)">'.e($name ?? strtoupper($code)).'</span>');
    }

    /**
     * @param  array<string, mixed>  $answers
     */
    private static function documentLinks(SupplierApplication $record, string $field, array $answers): ?HtmlString
    {
        $links = collect($answers[$field] ?? [])->map(function (array $file, int $index) use ($record, $field): string {
            $extension = strtoupper(pathinfo($file['path'] ?? $file['name'], PATHINFO_EXTENSION) ?: '?');
            $color = match ($extension) {
                'PDF' => '#c42b3b',
                'JPG', 'JPEG', 'PNG', 'WEBP' => '#2f7d8c',
                'XLSX' => '#1d7a45',
                default => '#6c6280',
            };

            return sprintf(
                '<div style="display:flex;align-items:center;gap:.5rem;padding:.2rem 0">'
                .'<span style="flex:none;min-width:2.4rem;padding:.1rem .3rem;border-radius:.25rem;background:%s;color:#fff;font-size:.65rem;font-weight:700;text-align:center">%s</span>'
                .'<a href="%s" style="color:var(--primary-600, #49037F);text-decoration:underline;text-underline-offset:2px;word-break:break-all">%s</a>'
                .'<span style="flex:none;opacity:.6;font-size:.8rem">%s</span></div>',
                $color,
                e($extension === 'JPEG' ? 'JPG' : $extension),
                e(route('supplier-documents.download', ['supplierApplication' => $record, 'field' => $field, 'index' => $index])),
                e($file['name']),
                e(str_replace(['KB', 'MB', ' B'], ['Ko', 'Mo', ' o'], Number::withLocale('fr', fn (): string => Number::fileSize($file['size'] ?? 0, maxPrecision: 1)))),
            );
        });

        return $links->isEmpty() ? null : new HtmlString($links->implode(''));
    }
}
