<?php

namespace App\Livewire;

use App\Enums\SupplierApplicationStatus;
use App\Livewire\Concerns\InteractsWithSupplierAnswers;
use App\Models\Page;
use App\Models\SupplierApplication;
use App\SupplierForms\FormSection;
use App\SupplierForms\SupplierForm;
use App\SupplierForms\SupplierOnboardingForm as OnboardingFormDefinition;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Form 2 — supplier onboarding, opened with the private link sent by Sabonea.
 * Filled in step by step, saved as a draft on every change.
 */
class SupplierOnboardingForm extends Component
{
    use InteractsWithSupplierAnswers;
    use WithFileUploads;

    private const MAX_FILES_PER_DOCUMENT = 10;

    #[Locked]
    public string $token = '';

    /**
     * Files being uploaded, per document type.
     *
     * @var array<string, mixed>
     */
    public array $uploads = [];

    public int $step = 0;

    public int $furthestStep = 0;

    public ?string $savedAt = null;

    public bool $submitted = false;

    public function mount(string $token): void
    {
        $this->locale = app()->getLocale();
        $this->applyLocale();
        $this->token = $token;

        $application = $this->application();

        if ($application === null) {
            return;
        }

        $definition = $this->definition();
        $this->answers = [
            ...$definition->emptyAnswers(),
            ...($application->onboarding_answers ?? $definition->prefill($application->contact_answers ?? [])),
        ];
        $this->submitted = $application->isOnboardingSubmitted();

        if ($application->onboarding_started_at === null) {
            $application->update(['onboarding_started_at' => now()]);
        }
    }

    /**
     * The application of the link, as long as the link is valid.
     */
    #[Computed]
    public function application(): ?SupplierApplication
    {
        return SupplierApplication::findByOnboardingToken($this->token);
    }

    public function next(): void
    {
        if (! $this->canEdit()) {
            return;
        }

        $this->answers = $this->definition()->prepare($this->answers);
        $this->validateAnswers($this->definition()->rules($this->answers, $this->currentSection()));

        $this->step = min($this->step + 1, count($this->definition()->sections()) - 1);
        $this->furthestStep = max($this->furthestStep, $this->step);
        $this->saveDraft();
        $this->dispatch('supplier-form-step');
    }

    public function previous(): void
    {
        $this->step = max($this->step - 1, 0);
        $this->resetErrorBag();
        $this->dispatch('supplier-form-step');
    }

    public function goToStep(int $step): void
    {
        if ($step >= 0 && $step <= $this->furthestStep) {
            $this->step = $step;
            $this->resetErrorBag();
            $this->dispatch('supplier-form-step');
        }
    }

    public function submit(): void
    {
        if (! $this->canEdit()) {
            return;
        }

        $definition = $this->definition();
        $this->answers = $definition->prepare($this->answers);

        try {
            $this->validateAnswers($definition->rules($this->answers));
        } catch (ValidationException $exception) {
            $this->step = $this->stepOfFirstError(array_keys($exception->errors()));

            throw $exception;
        }

        $this->application()->update([
            'status' => SupplierApplicationStatus::OnboardingSubmitted,
            'onboarding_answers' => $definition->normalize($this->answers),
            'onboarding_locale' => $this->locale,
            'onboarding_saved_at' => now(),
            'onboarding_submitted_at' => now(),
        ]);

        $this->submitted = true;
        $this->dispatch('supplier-form-done');
    }

    /**
     * Uploaded files are stored on the private disk right away, so the draft keeps them.
     */
    public function updatedUploads(mixed $value, string $key): void
    {
        $field = Str::before($key, '.');

        if (! $this->canEdit() || ! in_array($field, OnboardingFormDefinition::DOCUMENT_KEYS, true)) {
            $this->uploads[$field] = [];

            return;
        }

        $message = ui('supplier_form.file_error');

        try {
            $this->validate(
                ["uploads.{$field}.*" => ['file', 'mimes:pdf,jpg,jpeg,png,xlsx', 'max:20480']],
                ['*' => $message],
            );
        } catch (ValidationException $exception) {
            $this->uploads[$field] = [];

            throw $exception;
        }

        $files = (array) ($this->answers[$field] ?? []);

        foreach (Arr::wrap($this->uploads[$field] ?? []) as $upload) {
            if (count($files) >= self::MAX_FILES_PER_DOCUMENT) {
                $this->addError("uploads.{$field}", ui('supplier_form.file_limit'));
                break;
            }

            /** @var UploadedFile $upload */
            $files[] = [
                'path' => $upload->store($this->application()->documentsDirectory()."/{$field}", 'local'),
                'name' => $upload->getClientOriginalName(),
                'size' => $upload->getSize(),
            ];
        }

        $this->answers[$field] = $files;
        $this->uploads[$field] = [];
        $this->saveDraft();
    }

    public function removeFile(string $field, int $index): void
    {
        if (! $this->canEdit() || ! in_array($field, OnboardingFormDefinition::DOCUMENT_KEYS, true)) {
            return;
        }

        $files = (array) ($this->answers[$field] ?? []);

        if (isset($files[$index]['path'])) {
            Storage::disk('local')->delete($files[$index]['path']);
        }

        unset($files[$index]);
        $this->answers[$field] = array_values($files);
        $this->saveDraft();
    }

    protected function answerUpdated(string $key): void
    {
        $this->saveDraft();
    }

    protected function definition(): SupplierForm
    {
        return new OnboardingFormDefinition;
    }

    private function currentSection(): FormSection
    {
        return $this->definition()->sections()[$this->step];
    }

    private function canEdit(): bool
    {
        return $this->application() !== null && ! $this->application()->isOnboardingSubmitted();
    }

    private function saveDraft(): void
    {
        if (! $this->canEdit()) {
            return;
        }

        $this->application()->update([
            'onboarding_answers' => $this->answers,
            'onboarding_locale' => $this->locale,
            'onboarding_saved_at' => now(),
        ]);

        $this->savedAt = now()->format('H:i');
    }

    /**
     * @param  array<int, string>  $errorKeys  e.g. "answers.headcount"
     */
    private function stepOfFirstError(array $errorKeys): int
    {
        foreach ($this->definition()->sections() as $index => $section) {
            foreach ($section->questions as $question) {
                foreach ($errorKeys as $errorKey) {
                    if (Str::startsWith(Str::after($errorKey, 'answers.'), $question->key)) {
                        return $index;
                    }
                }
            }
        }

        return $this->step;
    }

    public function render(): View
    {
        $page = Page::query()->where('key', 'integration-fournisseur')->with('sections.items')->first();
        $definition = $this->definition();
        $progress = $definition->progress($this->answers);

        return view('livewire.supplier-onboarding-form', [
            'definition' => $definition,
            'sections' => $definition->sections(),
            'progress' => $progress,
            'percent' => $definition->progressPercent($progress),
            'page' => $page,
            'isValid' => $this->application() !== null,
        ]);
    }
}
