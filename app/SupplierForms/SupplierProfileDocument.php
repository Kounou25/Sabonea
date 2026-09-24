<?php

namespace App\SupplierForms;

use App\Models\Setting;
use App\Models\SupplierApplication;
use App\Support\Countries;
use App\Support\Locales;
use App\Support\Ui;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

/**
 * Supplier profile sheet (PDF), in two versions:
 * - internal: everything, with contact details, follow-up and internal notes;
 * - buyer: to be sent to a buyer, without any direct contact detail (introductions go through Sabonea).
 */
class SupplierProfileDocument
{
    public const INTERNAL = 'internal';

    public const BUYER = 'buyer';

    /**
     * Answers never shown to buyers: direct contact details and the supplier's relationship with Sabonea.
     *
     * @var array<int, string>
     */
    private const HIDDEN_FROM_BUYERS = [
        'contact_name', 'contact_role', 'contact_email', 'contact_phone', 'preferred_channel', 'preferred_language',
        'website', 'demo_video_url', 'hq_address', 'registration_number', 'annual_revenue',
        'interest_level', 'rfq_ready', 'expectations', 'source', 'collaboration_type', 'b2b_display_consent',
        'price_display', 'special_conditions', 'consent_contact', 'partnership_terms_ack', 'non_circumvention', 'consent_onboarding',
    ];

    /**
     * Answers displayed in the header, the key facts, the tags or the contact card, not repeated in the details.
     *
     * @var array<int, string>
     */
    private const OVERVIEW_KEYS = [
        'company_name', 'legal_name', 'country', 'hq_country', 'city', 'supplier_type', 'company_age', 'founding_date',
        'headcount', 'sales_languages', 'flagship_product', 'equipment_categories', 'sectors', 'certifications', 'export_countries',
        'contact_name', 'contact_role', 'contact_email', 'contact_phone', 'preferred_channel', 'preferred_language',
    ];

    /**
     * Answers of form 1 that have no equivalent in form 2.
     *
     * @var array<int, string>
     */
    private const FIRST_CONTACT_ONLY = ['exports', 'interest_level', 'rfq_ready', 'source'];

    /**
     * @var array<int, string>
     */
    private const COMMITMENTS = ['partnership_terms_ack', 'non_circumvention', 'consent_onboarding'];

    private const MEMORY_LIMIT = 512 * 1024 * 1024;

    public function __construct(
        public SupplierApplication $application,
        public string $audience = self::INTERNAL,
        public string $locale = 'fr',
    ) {
        $this->audience = $audience === self::BUYER ? self::BUYER : self::INTERNAL;
        $this->locale = array_key_exists($locale, Locales::all()) ? $locale : Locales::reference();
    }

    /**
     * @return array<string, string>
     */
    public static function audiences(): array
    {
        return [
            self::INTERNAL => 'Interne : complète, avec coordonnées et suivi',
            self::BUYER => 'Acheteur : sans coordonnées (mise en relation via Sabonea)',
        ];
    }

    public function isForBuyer(): bool
    {
        return $this->audience === self::BUYER;
    }

    public function pdf(): DomPdf
    {
        // Subsetting the Chinese font reads the whole file (10 MB per weight).
        if (ini_get('memory_limit') !== '-1' && ini_parse_quantity(ini_get('memory_limit')) < self::MEMORY_LIMIT) {
            ini_set('memory_limit', (string) self::MEMORY_LIMIT);
        }

        $this->ensureFontCache();

        $pdf = Pdf::setOptions([
            'chroot' => base_path(),
            'isRemoteEnabled' => false,
            'isFontSubsettingEnabled' => true,
            'defaultFont' => 'Poppins',
            'dpi' => 96,
        ], mergeWithDefaults: true)
            ->setPaper('a4');

        $this->registerChineseFont($pdf);
        $pdf->loadHTML($this->html())->render();
        $this->drawPageNumbers($pdf);

        return $pdf;
    }

    /**
     * dompdf computes the font metrics once and caches them in storage/fonts, with an index of the cached files.
     * It trusts that index blindly: if cached files were removed, the text silently falls back to Helvetica
     * (no Chinese glyphs). In that case the index is dropped so that every font is cached again.
     */
    private function ensureFontCache(): void
    {
        $directory = storage_path('fonts');
        $index = "{$directory}/installed-fonts.json";

        File::ensureDirectoryExists($directory);

        if (! File::exists($index)) {
            return;
        }

        $families = json_decode((string) File::get($index), true);

        foreach (collect(is_array($families) ? $families : [])->flatten()->filter(fn (mixed $variant): bool => is_string($variant)) as $variant) {
            $path = basename($variant) === $variant ? "{$directory}/{$variant}" : $variant;

            if (! File::exists("{$path}.ufm") || ! File::exists("{$path}.ttf")) {
                File::delete($index);

                return;
            }
        }
    }

    /**
     * Chinese fallback font (used glyph by glyph after Poppins). dompdf only matches exact weights,
     * so 500 and 600 point to the same files as regular and bold: each file is then embedded once.
     */
    private function registerChineseFont(DomPdf $pdf): void
    {
        $metrics = $pdf->getDomPDF()->getFontMetrics();

        foreach (['normal' => 'Regular', 'bold' => 'Bold'] as $weight => $file) {
            $metrics->registerFont(['family' => 'NotoSansSC', 'weight' => $weight, 'style' => 'normal'], resource_path("fonts/NotoSansSC-{$file}.ttf"));
        }

        $variants = $metrics->getFontFamilies()['notosanssc'] ?? [];
        $aliased = ['normal' => $variants['normal'] ?? null, 'bold' => $variants['bold'] ?? null, '500' => $variants['normal'] ?? null, '600' => $variants['bold'] ?? null];

        if (! in_array(null, $aliased, true) && $variants !== $aliased) {
            $metrics->setFontFamily('notosanssc', $aliased);
        }
    }

    /**
     * "Page X / Y" in the footer: the total is only known once the document is laid out.
     */
    private function drawPageNumbers(DomPdf $pdf): void
    {
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $metrics = $dompdf->getFontMetrics();
        $font = $metrics->getFont($this->locale === 'zh' ? 'NotoSansSC' : 'Poppins');
        $size = 7;
        $text = Ui::get('pdf.page', [], $this->locale).' {PAGE_NUM} / {PAGE_COUNT}';
        $width = $metrics->getTextWidth(str_replace(['{PAGE_NUM}', '{PAGE_COUNT}'], '88', $text), $font, $size);

        $canvas->page_text($canvas->get_width() - 30 - $width, $canvas->get_height() - 28, $text, $font, $size, [0.54, 0.50, 0.63]);
    }

    public function html(): string
    {
        return $this->inLocale(fn (): string => view('pdf.supplier-profile', $this->data())->render());
    }

    public function filename(): string
    {
        $company = Str::slug($this->application->company_name) ?: 'fournisseur';

        return "fiche-fournisseur-{$company}".($this->isForBuyer() ? '-acheteur' : '')."-{$this->locale}.pdf";
    }

    /**
     * Everything the view displays, already translated.
     *
     * @return array<string, mixed>
     */
    public function data(): array
    {
        $application = $this->application;
        $hasOnboarding = $application->isOnboardingSubmitted();
        $definition = $hasOnboarding ? new SupplierOnboardingForm : new SupplierContactForm;
        $answers = $hasOnboarding ? ($application->onboarding_answers ?? []) : ($application->contact_answers ?? []);
        $contactAnswers = $application->contact_answers ?? [];
        $all = [...$contactAnswers, ...$answers];
        $contactDefinition = new SupplierContactForm;

        return [
            'locale' => $this->locale,
            'isForBuyer' => $this->isForBuyer(),
            'fontsPath' => str_replace('\\', '/', resource_path('fonts')),
            'logoPath' => str_replace('\\', '/', public_path('img/sabonea/logo-sabonea-white.png')),
            'reference' => str_pad((string) $application->id, 5, '0', STR_PAD_LEFT),
            'generatedOn' => now()->locale($this->locale)->isoFormat('LL'),
            'companyName' => $all['legal_name'] ?? $application->company_name,
            'location' => collect([$all['city'] ?? null, Countries::name($all['hq_country'] ?? $all['country'] ?? null, $this->locale)])->filter()->implode(', '),
            'flagship' => $contactAnswers['flagship_product'] ?? null,
            'facts' => $this->facts($all, $definition),
            'tags' => $this->tags($all, $definition),
            'contact' => $this->isForBuyer() ? [] : $this->rows($contactDefinition, $all, ['contact_name', 'contact_role', 'contact_email', 'contact_phone', 'preferred_channel', 'preferred_language']),
            'followUp' => $this->isForBuyer() ? null : [
                'status' => Ui::get('pdf.status.'.$application->status->value, [], $this->locale),
                'contactReceived' => $application->contact_submitted_at?->locale($this->locale)->isoFormat('LL'),
                'onboardingReceived' => $application->onboarding_submitted_at?->locale($this->locale)->isoFormat('LL'),
            ],
            'onboardingPending' => ! $hasOnboarding,
            'sections' => $this->sections($definition, $answers, $contactDefinition, $contactAnswers, $hasOnboarding),
            'commitments' => $this->isForBuyer() || ! $hasOnboarding ? [] : $this->commitments($answers, $contactAnswers),
            'notes' => $this->isForBuyer() ? null : $application->internal_notes,
            'contactEmail' => Setting::get('contact_email'),
            'siteUrl' => preg_replace('#^https?://#', '', rtrim(url('/'), '/')),
        ];
    }

    /**
     * @param  array<string, mixed>  $answers
     * @return array<int, array{label: string, value: string}>
     */
    private function facts(array $answers, SupplierForm $definition): array
    {
        $contactDefinition = new SupplierContactForm;
        $facts = [
            ['label' => $this->label('supplier_type', $definition), 'value' => $this->value($contactDefinition, 'supplier_type', $answers)],
            filled($answers['founding_date'] ?? null)
                ? ['label' => Ui::get('pdf.founded', [], $this->locale), 'value' => (string) Carbon::parse($answers['founding_date'])->year]
                : ['label' => $this->label('company_age', $contactDefinition), 'value' => $this->value($contactDefinition, 'company_age', $answers)],
            ['label' => $this->label('headcount', $definition), 'value' => $this->value(new SupplierOnboardingForm, 'headcount', $answers)],
            ['label' => $this->label('sales_languages', $contactDefinition), 'value' => $this->value($contactDefinition, 'sales_languages', $answers)],
        ];

        return array_values(array_filter($facts, fn (array $fact): bool => filled($fact['value'])));
    }

    /**
     * Tag groups: equipment, sectors, certifications, export countries.
     *
     * @param  array<string, mixed>  $answers
     * @return array<int, array{label: string, style: string, items: array<int, string>}>
     */
    private function tags(array $answers, SupplierForm $definition): array
    {
        $groups = [
            ['key' => 'equipment_categories', 'list' => OptionLists::EQUIPMENT, 'style' => 'purple'],
            ['key' => 'sectors', 'list' => OptionLists::SECTORS, 'style' => 'green'],
            ['key' => 'certifications', 'list' => 'certifications', 'style' => 'orange'],
            ['key' => 'export_countries', 'list' => null, 'style' => 'grey'],
        ];

        $tags = [];

        foreach ($groups as $group) {
            $keys = (array) ($answers[$group['key']] ?? []);

            if ($keys === []) {
                continue;
            }

            $question = $definition->question($group['key']) ?? (new SupplierContactForm)->question($group['key']);

            $items = array_map(fn (string $key): string => $group['list'] === null
                ? (string) Countries::name($key, $this->locale)
                : $definition->optionWithDetail($question, $key, $answers, $this->locale), $keys);

            $tags[] = ['label' => $this->label($group['key'], $definition), 'style' => $group['style'], 'items' => $items];
        }

        return $tags;
    }

    /**
     * @param  array<string, mixed>  $answers
     * @param  array<string, mixed>  $contactAnswers
     * @return array<int, array{title: string, rows: array<int, array{label: string, value: string, long: bool}>}>
     */
    private function sections(SupplierForm $definition, array $answers, SupplierForm $contactDefinition, array $contactAnswers, bool $hasOnboarding): array
    {
        $sections = [];

        foreach ($definition->sections() as $section) {
            $keys = array_map(fn (Question $question): string => $question->key, array_filter(
                $section->questions,
                fn (Question $question): bool => ! in_array($question->type, [FieldType::Files, FieldType::Consent], true)
                    && ! in_array($question->key, self::OVERVIEW_KEYS, true),
            ));

            $rows = $this->rows($definition, $answers, $keys);

            // Uploaded files are listed in their own section, with the demonstration video link.
            if ($section->key === 'documents') {
                $rows = [...$this->documentRows($answers), ...$rows];
            }

            if ($rows !== []) {
                $sections[] = ['title' => $definition->sectionTitle($section, $this->locale), 'rows' => $rows];
            }
        }

        if ($hasOnboarding && ! $this->isForBuyer()) {
            $rows = $this->rows($contactDefinition, $contactAnswers, self::FIRST_CONTACT_ONLY);

            if ($rows !== []) {
                $sections[] = ['title' => Ui::get('pdf.first_contact', [], $this->locale), 'rows' => $rows];
            }
        }

        return $sections;
    }

    /**
     * Answered questions, as label / value rows (hidden answers of the buyer version left out).
     *
     * @param  array<string, mixed>  $answers
     * @param  array<int, string>  $keys
     * @return array<int, array{label: string, value: string, long: bool}>
     */
    private function rows(SupplierForm $definition, array $answers, array $keys): array
    {
        $rows = [];

        foreach ($keys as $key) {
            $question = $definition->question($key);

            if ($question === null || ($this->isForBuyer() && in_array($key, self::HIDDEN_FROM_BUYERS, true))) {
                continue;
            }

            $value = $this->value($definition, $key, $answers);

            if (filled($value)) {
                $rows[] = [
                    'label' => $this->label($key, $definition),
                    'value' => $value,
                    'long' => $question->type === FieldType::Textarea,
                ];
            }
        }

        return $rows;
    }

    /**
     * One row per type of document sent: file names (internal) or "available on request" (buyer).
     *
     * @param  array<string, mixed>  $answers
     * @return array<int, array{label: string, value: string, long: bool}>
     */
    private function documentRows(array $answers): array
    {
        $definition = new SupplierOnboardingForm;
        $rows = [];

        foreach (SupplierOnboardingForm::DOCUMENT_KEYS as $key) {
            $files = (array) ($answers[$key] ?? []);

            if ($files === []) {
                continue;
            }

            $rows[] = [
                'label' => $definition->plainLabel($definition->question($key), $this->locale),
                'value' => $this->isForBuyer()
                    ? Ui::get('pdf.files', ['count' => (string) count($files)], $this->locale).' · '.Ui::get('pdf.documents_on_request', [], $this->locale)
                    : implode("\n", array_map(fn (array $file): string => $file['name'].' ('.Number::fileSize($file['size'] ?? 0, precision: 1).')', $files)),
                'long' => false,
            ];
        }

        return $rows;
    }

    /**
     * @param  array<string, mixed>  $answers
     * @param  array<string, mixed>  $contactAnswers
     * @return array<int, array{label: string, accepted: bool}>
     */
    private function commitments(array $answers, array $contactAnswers): array
    {
        $commitments = array_map(fn (string $key): array => [
            'label' => Ui::get("supplier_pdf.{$key}", [], $this->locale),
            'accepted' => (bool) ($answers[$key] ?? false),
        ], self::COMMITMENTS);

        $commitments[] = ['label' => Ui::get('supplier_pdf.consent_contact', [], $this->locale), 'accepted' => (bool) ($contactAnswers['consent_contact'] ?? false)];

        return $commitments;
    }

    private function label(string $key, SupplierForm $definition): string
    {
        if (Ui::has("supplier_pdf.{$key}")) {
            return Ui::get("supplier_pdf.{$key}", [], $this->locale);
        }

        $question = $definition->question($key);

        return $question ? $definition->plainLabel($question, $this->locale) : $key;
    }

    /**
     * @param  array<string, mixed>  $answers
     */
    private function value(SupplierForm $definition, string $key, array $answers): ?string
    {
        $question = $definition->question($key);

        if ($question === null) {
            return null;
        }

        if ($question->type === FieldType::Date && filled($answers[$key] ?? null)) {
            return Carbon::parse($answers[$key])->locale($this->locale)->isoFormat('LL');
        }

        return $definition->displayValue($question, $answers, $this->locale);
    }

    /**
     * Renders in the document language (interface strings, dates), then restores the current one.
     *
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    private function inLocale(callable $callback): mixed
    {
        $previous = app()->getLocale();
        app()->setLocale($this->locale);

        try {
            return $callback();
        } finally {
            app()->setLocale($previous);
        }
    }
}
