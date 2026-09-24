<?php

namespace App\Livewire;

use App\Enums\SupplierApplicationStatus;
use App\Livewire\Concerns\InteractsWithSupplierAnswers;
use App\Models\Page;
use App\Models\SupplierApplication;
use App\SupplierForms\SupplierContactForm as ContactFormDefinition;
use App\SupplierForms\SupplierForm;
use App\Support\SupplierForms;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

/**
 * Form 1 — supplier contact, public page "Devenir fournisseur".
 */
class SupplierContactForm extends Component
{
    use InteractsWithSupplierAnswers;

    private const MAX_SUBMISSIONS_PER_HOUR = 5;

    /**
     * Honeypot: hidden to humans, filled in by bots.
     */
    public string $fax = '';

    public bool $submitted = false;

    public function mount(): void
    {
        $this->locale = app()->getLocale();
        $this->applyLocale();
        $this->answers = $this->definition()->emptyAnswers();
        $this->answers['preferred_language'] = 'LANG_'.strtoupper($this->locale);
    }

    public function submit(): void
    {
        abort_unless(SupplierForms::contactFormIsOpen(), 403);

        if (filled($this->fax)) {
            $this->submitted = true;

            return;
        }

        $throttleKey = 'supplier-contact:'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_SUBMISSIONS_PER_HOUR)) {
            $this->addError('form', ui('form.error_throttle'));

            return;
        }

        $definition = $this->definition();
        $this->answers = $definition->prepare($this->answers);
        $this->validateAnswers($definition->rules($this->answers));

        RateLimiter::hit($throttleKey, 3600);

        $answers = $definition->normalize($this->answers);

        SupplierApplication::create([
            'status' => SupplierApplicationStatus::New,
            'is_test' => SupplierForms::isTestSubmission(),
            'company_name' => $answers['company_name'],
            'country' => $answers['country'],
            'contact_name' => $answers['contact_name'],
            'contact_email' => $answers['contact_email'],
            'contact_phone' => $answers['contact_phone'],
            'contact_answers' => $answers,
            'contact_locale' => $this->locale,
            'contact_submitted_at' => now(),
            'ip_address' => request()->ip(),
        ]);

        $this->submitted = true;
        $this->dispatch('supplier-form-done');
    }

    protected function definition(): SupplierForm
    {
        return new ContactFormDefinition;
    }

    public function render(): View
    {
        $page = Page::query()->where('key', 'devenir-fournisseur')->with('sections.items')->first();
        $definition = $this->definition();
        $progress = $definition->progress($this->answers);

        return view('livewire.supplier-contact-form', [
            'definition' => $definition,
            'progress' => $progress,
            'percent' => $definition->progressPercent($progress),
            'thanks' => $page?->section('thanks'),
            'nextSteps' => $page?->section('next_steps'),
            'isTest' => SupplierForms::isTestSubmission(),
        ]);
    }
}
