<?php

namespace App\SupplierForms;

use App\Models\Setting;
use App\Models\SupplierApplication;
use App\Pdf\DompdfRenderer;
use App\SupplierForms\Profile\ProfileAnnexes;
use App\SupplierForms\Profile\ProfileAttachment;
use App\Support\Countries;
use App\Support\Locales;
use App\Support\Ui;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SplFileInfo;

/**
 * Supplier profile sheet (PDF), in two versions:
 * - internal: everything, with contact details, follow-up and internal notes;
 * - buyer: to be sent to a buyer, without any direct contact detail (introductions go through Sabonea).
 *
 * The PDF documents sent by the supplier can be appended as annexes; the other files are only listed.
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
     * Answers displayed on the first page (header, key facts, tags, contact card), not repeated in the details.
     *
     * @var array<int, string>
     */
    private const OVERVIEW_KEYS = [
        'company_name', 'legal_name', 'country', 'hq_country', 'city', 'supplier_type', 'company_age', 'founding_date',
        'headcount', 'sales_languages', 'flagship_product', 'equipment_categories', 'sectors', 'certifications', 'export_countries',
        'manufacturing_countries', 'contact_name', 'contact_role', 'contact_email', 'contact_phone', 'preferred_channel', 'preferred_language',
    ];

    /**
     * Buying conditions summarised on the first page.
     *
     * @var array<int, string>
     */
    private const KEY_POINTS = ['lead_time', 'warranty_duration', 'after_sales', 'spare_parts', 'payment_terms', 'quote_response_time', 'incoterms', 'customization'];

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

    /** Generated PDF files are kept next to the supplier documents, so they are deleted with the file. */
    private const CACHE_DIRECTORY = 'pdf-cache';

    private const CACHE_MINUTES = 15;

    /**
     * @var array<int, string> ids of the PDF documents appended as annexes
     */
    public array $documents;

    private ?ProfileAnnexes $annexes = null;

    /**
     * @param  array<int, string>|null  $documents  ids of the PDF documents to append (see documentOptions()), null for the default of the version
     */
    public function __construct(
        public SupplierApplication $application,
        public string $audience = self::INTERNAL,
        public string $locale = 'fr',
        ?array $documents = null,
    ) {
        $this->audience = $audience === self::BUYER ? self::BUYER : self::INTERNAL;
        $this->locale = array_key_exists($locale, Locales::all()) ? $locale : Locales::reference();
        $this->documents = array_values(array_intersect(
            $documents ?? self::defaultDocuments($application, $this->audience),
            array_keys(self::documentOptions($application)),
        ));
    }

    /**
     * @return array<string, string>
     */
    public static function audiences(): array
    {
        return [
            self::INTERNAL => 'Interne (complète)',
            self::BUYER => 'Acheteur (sans coordonnées)',
        ];
    }

    /**
     * PDF documents that can be appended to the profile, for the back-office choice: id => file name.
     *
     * @return array<string, string>
     */
    public static function documentOptions(SupplierApplication $application): array
    {
        return collect(ProfileAttachment::all($application))
            ->filter(fn (ProfileAttachment $attachment): bool => $attachment->isPdf())
            ->mapWithKeys(fn (ProfileAttachment $attachment): array => [$attachment->id => $attachment->name])
            ->all();
    }

    /**
     * Type of document, format and size of each option ("Fiches techniques · PDF, 2,3 Mo").
     *
     * @return array<string, string>
     */
    public static function documentDescriptions(SupplierApplication $application): array
    {
        $definition = new SupplierOnboardingForm;

        return collect(ProfileAttachment::all($application))
            ->filter(fn (ProfileAttachment $attachment): bool => $attachment->isPdf())
            ->mapWithKeys(fn (ProfileAttachment $attachment): array => [
                $attachment->id => $definition->plainLabel($definition->question($attachment->field), Locales::reference())." · {$attachment->formattedSize()}",
            ])
            ->all();
    }

    /**
     * Internal version: every PDF. Buyer version: none, since brochures and price lists often carry
     * the supplier's contact details (to be checked before adding them).
     *
     * @return array<int, string>
     */
    public static function defaultDocuments(SupplierApplication $application, string $audience): array
    {
        return $audience === self::BUYER ? [] : array_keys(self::documentOptions($application));
    }

    public function isForBuyer(): bool
    {
        return $this->audience === self::BUYER;
    }

    /**
     * The PDF file: the profile, then the PDF documents chosen as annexes.
     * Kept a few minutes, so that downloading right after the preview is immediate.
     */
    public function output(): string
    {
        $disk = Storage::disk('local');
        $directory = $this->application->documentsDirectory().'/'.self::CACHE_DIRECTORY;
        $path = "{$directory}/{$this->cacheKey()}.pdf";
        $expired = now()->subMinutes(self::CACHE_MINUTES)->getTimestamp();

        if ($disk->exists($path) && $disk->lastModified($path) > $expired) {
            return (string) $disk->get($path);
        }

        $content = $this->build();

        foreach ($disk->files($directory) as $file) {
            if ($disk->lastModified($file) <= $expired) {
                $disk->delete($file);
            }
        }

        $disk->put($path, $content);

        return $content;
    }

    /**
     * Everything the PDF depends on: the file and its documents, the options, the texts and the templates.
     */
    private function cacheKey(): string
    {
        return sha1((string) json_encode([
            $this->application->getKey(),
            $this->application->updated_at?->getTimestamp(),
            $this->audience,
            $this->locale,
            $this->documents,
            array_map(fn (ProfileAttachment $attachment): array => [$attachment->path, $attachment->size], ProfileAttachment::all($this->application)),
            md5(serialize(Ui::all())),
            Setting::get('contact_email'),
            now()->toDateString(),
            array_map(fn (SplFileInfo $file): int => (int) $file->getMTime(), File::allFiles(resource_path('views/pdf'))),
        ]));
    }

    private function build(): string
    {
        $renderer = new DompdfRenderer;
        $annexes = $this->annexes();
        $annexPages = $annexes->pageCount();

        $profile = $renderer->render(
            $this->html(),
            pageLabel: fn (int $page, int $pageCount): string => $this->pageLabel($page, $pageCount + $annexPages),
            chinese: $this->locale === 'zh',
        );

        if ($annexes->isEmpty()) {
            return $profile->content;
        }

        $layout = $annexes->layout($profile->pageCount);
        $total = $profile->pageCount + $annexPages;
        $withCategory = fn (array $item): array => [...$item, 'category' => $this->documentLabel($item['attachment'])];
        $contents = ['page' => $layout['contents']['page'], 'annexes' => array_map($withCategory, $layout['contents']['annexes'])];
        $framePdfs = [];

        foreach (['portrait', 'landscape'] as $orientation) {
            $frames = array_values(array_filter($layout['frames'], fn (array $frame): bool => $frame['orientation'] === $orientation));

            // The portrait document always exists: it starts with the contents page of the annexes.
            if ($orientation === 'portrait' || $frames !== []) {
                $framePdfs[$orientation] = $renderer->render($this->inLocale(fn (): string => view('pdf.supplier-profile-annexes', [
                    ...$this->common(),
                    'orientation' => $orientation,
                    'contents' => $orientation === 'portrait' ? $contents : null,
                    'frames' => array_map($withCategory, $frames),
                    'total' => $total,
                ])->render()), $orientation);
            }
        }

        $title = Ui::get('pdf.title', [], $this->locale).' — '.$this->companyName();

        return $annexes->assemble($profile, $layout['frames'], $framePdfs, [
            'profile' => $title,
            'contents' => Ui::get('pdf.annexes', [], $this->locale),
            'annexes' => collect($layout['contents']['annexes'])->mapWithKeys(fn (array $annex): array => [
                $annex['code'] => "{$annex['code']} · {$this->documentLabel($annex['attachment'])} — {$annex['attachment']->name}",
            ])->all(),
        ], $title);
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
     * Everything the profile view displays, already translated.
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
            ...$this->common(),
            'location' => collect([$all['city'] ?? null, Countries::name($all['hq_country'] ?? $all['country'] ?? null, $this->locale)])->filter()->implode(', '),
            'flagship' => $contactAnswers['flagship_product'] ?? null,
            'facts' => $this->facts($all, $definition),
            'keyPoints' => $hasOnboarding ? $this->keyPoints($answers, $definition) : [],
            'tags' => $this->tags($all, $definition),
            'contact' => $this->isForBuyer() ? [] : $this->rows($contactDefinition, $all, ['contact_name', 'contact_role', 'contact_email', 'contact_phone', 'preferred_channel', 'preferred_language']),
            'followUp' => $this->isForBuyer() ? null : [
                'status' => Ui::get('pdf.status.'.$application->status->value, [], $this->locale),
                'contactReceived' => $application->contact_submitted_at?->locale($this->locale)->isoFormat('LL'),
                'onboardingReceived' => $application->onboarding_submitted_at?->locale($this->locale)->isoFormat('LL'),
                'commitments' => $hasOnboarding ? $this->commitments($answers, $contactAnswers) : [],
            ],
            'onboardingPending' => ! $hasOnboarding,
            'notes' => $this->isForBuyer() ? null : $application->internal_notes,
            'sections' => $this->sections($definition, $answers, $contactDefinition, $contactAnswers, $hasOnboarding),
            'siteUrl' => preg_replace('#^https?://#', '', rtrim(url('/'), '/')),
        ];
    }

    /**
     * Data shared by the profile and the annex pages (header and footer).
     *
     * @return array<string, mixed>
     */
    private function common(): array
    {
        $contactEmail = Setting::get('contact_email');

        return [
            'locale' => $this->locale,
            'isForBuyer' => $this->isForBuyer(),
            'fontsPath' => str_replace('\\', '/', resource_path('fonts')),
            'logoPath' => str_replace('\\', '/', public_path('img/sabonea/logo-sabonea-white.png')),
            'reference' => str_pad((string) $this->application->id, 5, '0', STR_PAD_LEFT),
            'generatedOn' => now()->locale($this->locale)->isoFormat('LL'),
            'companyName' => $this->companyName(),
            'contactEmail' => $contactEmail,
            'footer' => 'Sabonea · Direct access to the best suppliers · '.($this->isForBuyer()
                ? Ui::get('pdf.footer_buyer', ['email' => $contactEmail], $this->locale)
                : Ui::get('pdf.footer_internal', [], $this->locale)),
            'pageLabel' => fn (int $page, int $pageCount): string => $this->pageLabel($page, $pageCount),
        ];
    }

    private function companyName(): string
    {
        $legalName = $this->application->isOnboardingSubmitted() ? ($this->application->onboarding_answers['legal_name'] ?? null) : null;

        return filled($legalName) ? (string) $legalName : $this->application->company_name;
    }

    private function pageLabel(int $page, int $pageCount): string
    {
        return Ui::get('pdf.page', [], $this->locale)." {$page} / {$pageCount}";
    }

    /**
     * The PDF documents chosen, imported once (their pages are needed before laying out the profile).
     */
    private function annexes(): ProfileAnnexes
    {
        if ($this->annexes === null) {
            DompdfRenderer::raiseLimits();
            $this->annexes = new ProfileAnnexes;

            foreach ($this->chosenAttachments() as $attachment) {
                if ($attachment->isPdf()) {
                    $this->annexes->add($attachment);
                }
            }
        }

        return $this->annexes;
    }

    /**
     * @return array<int, ProfileAttachment>
     */
    private function chosenAttachments(): array
    {
        return array_values(array_filter(
            ProfileAttachment::all($this->application),
            fn (ProfileAttachment $attachment): bool => in_array($attachment->id, $this->documents, true),
        ));
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
     * @param  array<string, mixed>  $answers
     * @return array<int, array{label: string, value: string}>
     */
    private function keyPoints(array $answers, SupplierForm $definition): array
    {
        $points = [];

        foreach (self::KEY_POINTS as $key) {
            $value = $this->value($definition, $key, $answers);

            if (filled($value)) {
                $points[] = ['label' => $this->label($key, $definition), 'value' => $value];
            }
        }

        return $points;
    }

    /**
     * Tag groups: equipment, sectors, certifications, countries.
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
            ['key' => 'manufacturing_countries', 'list' => null, 'style' => 'grey'],
            ['key' => 'export_countries', 'list' => null, 'style' => 'grey'],
        ];

        $tags = [];

        foreach ($groups as $group) {
            $keys = (array) ($answers[$group['key']] ?? []);
            $question = $definition->question($group['key']) ?? (new SupplierContactForm)->question($group['key']);

            if ($keys === [] || $question === null) {
                continue;
            }

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
     * @return array<int, array<string, mixed>>
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

            $current = [
                'title' => $definition->sectionTitle($section, $this->locale),
                'fields' => $this->rows($definition, $answers, $keys),
                'documents' => $section->key === 'documents' ? $this->documentList() : null,
            ];

            if ($current['fields'] !== [] || ($current['documents']['rows'] ?? []) !== [] || ($current['documents']['onRequest'] ?? 0) > 0) {
                $sections[] = $current;
            }
        }

        if ($hasOnboarding && ! $this->isForBuyer()) {
            $fields = $this->rows($contactDefinition, $contactAnswers, self::FIRST_CONTACT_ONLY);

            if ($fields !== []) {
                $sections[] = ['title' => Ui::get('pdf.first_contact', [], $this->locale), 'fields' => $fields, 'documents' => null];
            }
        }

        foreach ($sections as $index => $section) {
            $sections[$index]['number'] = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
            $sections[$index]['lines'] = $this->pairFields($section['fields']);
        }

        return $sections;
    }

    /**
     * Fields laid out two by two; long answers take the whole width.
     *
     * @param  array<int, array{label: string, value: string, wide: bool, tone: ?string}>  $fields
     * @return array<int, array<int, array{label: string, value: string, wide: bool, tone: ?string}>>
     */
    private function pairFields(array $fields): array
    {
        $lines = [];
        $pending = null;

        foreach ($fields as $field) {
            if ($field['wide']) {
                if ($pending !== null) {
                    $lines[] = [$pending];
                    $pending = null;
                }

                $lines[] = [$field];

                continue;
            }

            if ($pending === null) {
                $pending = $field;
            } else {
                $lines[] = [$pending, $field];
                $pending = null;
            }
        }

        if ($pending !== null) {
            $lines[] = [$pending];
        }

        return $lines;
    }

    /**
     * Answered questions (hidden answers of the buyer version left out). "Yes" and "No" are shown as badges.
     *
     * @param  array<string, mixed>  $answers
     * @param  array<int, string>  $keys
     * @return array<int, array{label: string, value: string, wide: bool, tone: ?string}>
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
                    'wide' => $question->type === FieldType::Textarea || str_contains($value, "\n") || mb_strlen($value) > 70,
                    'tone' => match ($question->type === FieldType::Choice ? ($answers[$key] ?? null) : null) {
                        'YES' => 'yes',
                        'NO' => 'no',
                        default => null,
                    },
                ];
            }
        }

        return $rows;
    }

    /**
     * Documents sent by the supplier and where to find them. Buyers only see the ones appended as annexes,
     * the others are counted as "available on request".
     *
     * @return array{rows: array<int, array{type: string, category: string, name: string, size: string, status: string, tone: string}>, onRequest: int}
     */
    private function documentList(): array
    {
        $rows = [];
        $onRequest = 0;

        foreach (ProfileAttachment::all($this->application) as $attachment) {
            $code = $this->annexes()->codeOf($attachment);

            [$status, $tone] = match (true) {
                $code !== null => [Ui::get('pdf.document_annex', ['code' => $code], $this->locale), 'annex'],
                $this->isForBuyer() => [null, null],
                $attachment->absolutePath() === null => [Ui::get('pdf.document_missing', [], $this->locale), 'warning'],
                $this->annexes()->isUnreadable($attachment) => [Ui::get('pdf.document_unreadable', [], $this->locale), 'warning'],
                default => [Ui::get('pdf.document_not_included', [], $this->locale), 'muted'],
            };

            if ($status === null) {
                $onRequest++;

                continue;
            }

            $rows[] = [
                'type' => $attachment->type(),
                'category' => $this->documentLabel($attachment),
                'name' => $attachment->name,
                'size' => $attachment->formattedSize($this->locale),
                'status' => $status,
                'tone' => $tone,
            ];
        }

        return ['rows' => $rows, 'onRequest' => $onRequest];
    }

    private function documentLabel(ProfileAttachment $attachment): string
    {
        return $this->label($attachment->field, new SupplierOnboardingForm);
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
