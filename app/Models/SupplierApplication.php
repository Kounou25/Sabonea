<?php

namespace App\Models;

use App\Enums\SupplierApplicationStatus;
use App\SupplierForms\SupplierOnboardingForm;
use App\Support\Locales;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'status', 'is_test', 'company_name', 'country', 'contact_name', 'contact_email', 'contact_phone',
    'contact_answers', 'contact_locale', 'contact_submitted_at',
    'onboarding_token', 'onboarding_token_expires_at', 'onboarding_answers', 'onboarding_locale',
    'onboarding_started_at', 'onboarding_saved_at', 'onboarding_submitted_at',
    'internal_notes', 'ip_address',
])]
class SupplierApplication extends Model
{
    /** Private disk folder of the documents uploaded with form 2. */
    public const DOCUMENTS_DIRECTORY = 'supplier-documents';

    public const ONBOARDING_LINK_DAYS = 30;

    protected static function booted(): void
    {
        static::deleted(function (SupplierApplication $application): void {
            Storage::disk('local')->deleteDirectory($application->documentsDirectory());
        });
    }

    /**
     * Test answers (filled in by a back-office user) are left out of the exports.
     *
     * @param  Builder<self>  $query
     */
    #[Scope]
    protected function real(Builder $query): void
    {
        $query->where('is_test', false);
    }

    /**
     * Application whose private link to form 2 is currently usable.
     */
    public static function findByOnboardingToken(?string $token): ?self
    {
        if (blank($token)) {
            return null;
        }

        $application = static::query()->where('onboarding_token', $token)->first();

        return $application?->hasValidOnboardingLink() ? $application : null;
    }

    public function hasValidOnboardingLink(): bool
    {
        return filled($this->onboarding_token)
            && $this->onboarding_token_expires_at?->isFuture()
            && in_array($this->status, [SupplierApplicationStatus::Approved, SupplierApplicationStatus::OnboardingSubmitted], true);
    }

    public function isOnboardingSubmitted(): bool
    {
        return $this->onboarding_submitted_at !== null;
    }

    /**
     * Validates the contact and creates (or renews) the private link to form 2.
     */
    public function approve(): void
    {
        $this->update([
            'status' => SupplierApplicationStatus::Approved,
            'onboarding_token' => Str::random(64),
            'onboarding_token_expires_at' => now()->addDays(self::ONBOARDING_LINK_DAYS),
            'onboarding_answers' => $this->onboarding_answers ?? (new SupplierOnboardingForm)->prefill($this->contact_answers ?? []),
        ]);
    }

    public function revokeOnboardingLink(): void
    {
        $this->update(['onboarding_token' => null, 'onboarding_token_expires_at' => null]);
    }

    public function onboardingUrl(?string $locale = null): ?string
    {
        if (blank($this->onboarding_token)) {
            return null;
        }

        return route('integration-fournisseur', [
            'locale' => $locale ?? $this->preferredLocale(),
            'token' => $this->onboarding_token,
        ]);
    }

    /**
     * Site language matching the contact language chosen in form 1.
     */
    public function preferredLocale(): string
    {
        $locale = match ($this->contact_answers['preferred_language'] ?? null) {
            'LANG_EN' => 'en',
            'LANG_FR' => 'fr',
            'LANG_DE' => 'de',
            'LANG_ZH' => 'zh',
            default => $this->contact_locale,
        };

        return Locales::isActive((string) $locale) ? $locale : Locales::default();
    }

    public function documentsDirectory(): string
    {
        return self::DOCUMENTS_DIRECTORY.'/'.$this->getKey();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => SupplierApplicationStatus::class,
            'is_test' => 'boolean',
            'contact_answers' => 'array',
            'contact_submitted_at' => 'datetime',
            'onboarding_token_expires_at' => 'datetime',
            'onboarding_answers' => 'array',
            'onboarding_started_at' => 'datetime',
            'onboarding_saved_at' => 'datetime',
            'onboarding_submitted_at' => 'datetime',
        ];
    }
}
