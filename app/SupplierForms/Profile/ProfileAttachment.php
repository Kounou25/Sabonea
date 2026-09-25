<?php

namespace App\SupplierForms\Profile;

use App\Models\SupplierApplication;
use App\SupplierForms\SupplierOnboardingForm;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

/**
 * A file sent with form 2 (price list, datasheet, photo...), as displayed in the profile PDF.
 */
final readonly class ProfileAttachment
{
    public function __construct(
        public string $id,
        public string $field,
        public string $path,
        public string $name,
        public int $size,
    ) {}

    /**
     * Files of the submitted onboarding file (a draft is not shown).
     *
     * @return array<int, self>
     */
    public static function all(SupplierApplication $application): array
    {
        if (! $application->isOnboardingSubmitted()) {
            return [];
        }

        $answers = $application->onboarding_answers ?? [];
        $attachments = [];

        foreach (SupplierOnboardingForm::DOCUMENT_KEYS as $field) {
            foreach (array_values((array) ($answers[$field] ?? [])) as $index => $file) {
                if (is_array($file) && filled($file['path'] ?? null)) {
                    $attachments[] = new self("{$field}.{$index}", $field, $file['path'], (string) ($file['name'] ?? basename($file['path'])), (int) ($file['size'] ?? 0));
                }
            }
        }

        return $attachments;
    }

    /**
     * Stored extension (given by the file content at upload), not the one of the original name.
     */
    public function extension(): string
    {
        return strtolower(pathinfo($this->path, PATHINFO_EXTENSION) ?: pathinfo($this->name, PATHINFO_EXTENSION));
    }

    /**
     * Only PDF documents can be shown inside the profile (as annexes); the others are just listed.
     */
    public function isPdf(): bool
    {
        return $this->extension() === 'pdf';
    }

    public function type(): string
    {
        return match (true) {
            $this->isPdf() => 'PDF',
            in_array($this->extension(), ['jpg', 'jpeg'], true) => 'JPG',
            default => strtoupper($this->extension()) ?: '—',
        };
    }

    public function formattedSize(string $locale = 'fr'): string
    {
        $size = Number::withLocale($locale, fn (): string => Number::fileSize($this->size, maxPrecision: 1));

        // French writes octets: Ko, Mo.
        return $locale === 'fr' ? str_replace(['KB', 'MB', 'GB', ' B'], ['Ko', 'Mo', 'Go', ' o'], $size) : $size;
    }

    public function absolutePath(): ?string
    {
        return Storage::disk('local')->exists($this->path) ? Storage::disk('local')->path($this->path) : null;
    }
}
