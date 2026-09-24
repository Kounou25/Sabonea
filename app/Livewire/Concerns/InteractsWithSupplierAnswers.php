<?php

namespace App\Livewire\Concerns;

use App\SupplierForms\FieldType;
use App\SupplierForms\OptionLists;
use App\SupplierForms\SupplierForm;
use App\Support\Countries;
use App\Support\Locales;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;

trait InteractsWithSupplierAnswers
{
    /**
     * Language of the page the form was opened on: Livewire updates don't go through the {locale} route.
     */
    #[Locked]
    public string $locale = 'fr';

    /**
     * @var array<string, mixed>
     */
    public array $answers = [];

    /**
     * Answers before the current update (not persisted between requests).
     *
     * @var array<string, mixed>
     */
    private array $answersBeforeUpdate = [];

    abstract protected function definition(): SupplierForm;

    /**
     * Hook called after each change of an answer.
     */
    protected function answerUpdated(string $key): void {}

    public function hydrateInteractsWithSupplierAnswers(): void
    {
        $this->applyLocale();
    }

    protected function applyLocale(): void
    {
        if (Locales::isActive($this->locale)) {
            app()->setLocale($this->locale);
            URL::defaults(['locale' => $this->locale]);
        }
    }

    public function updatingAnswers(mixed $value, string $key): void
    {
        $field = Str::before($key, '.');
        $this->answersBeforeUpdate[$field] = $this->answers[$field] ?? null;
    }

    /**
     * "None" is exclusive: selecting it unselects the other options, and the other way round.
     */
    public function updatedAnswers(mixed $value, string $key): void
    {
        $field = Str::before($key, '.');
        $question = $this->definition()->question($field);

        if ($question?->type === FieldType::Choices && is_array($this->answers[$field] ?? null)) {
            $selected = array_values($this->answers[$field]);
            $exclusive = OptionLists::exclusiveKeys($question->list);
            $added = array_diff($selected, (array) ($this->answersBeforeUpdate[$field] ?? []));

            if (array_intersect($added, $exclusive) !== []) {
                $this->answers[$field] = array_values(array_intersect($added, $exclusive));
            } elseif ($added !== [] && array_intersect($selected, $exclusive) !== []) {
                $this->answers[$field] = array_values(array_diff($selected, $exclusive));
            }
        }

        $this->answerUpdated($field);
    }

    public function addCountry(string $key, string $code): void
    {
        if ($this->definition()->question($key)?->type !== FieldType::Countries || ! in_array($code, Countries::CODES, true)) {
            return;
        }

        $countries = (array) ($this->answers[$key] ?? []);

        if (! in_array($code, $countries, true)) {
            $this->answers[$key] = [...$countries, $code];
            $this->resetErrorBag("answers.{$key}");
            $this->answerUpdated($key);
        }
    }

    public function removeCountry(string $key, string $code): void
    {
        if ($this->definition()->question($key)?->type !== FieldType::Countries) {
            return;
        }

        $this->answers[$key] = array_values(array_diff((array) ($this->answers[$key] ?? []), [$code]));
        $this->answerUpdated($key);
    }

    /**
     * Validates the answers; the page scrolls to the first error when it fails.
     *
     * @param  array<string, array<int, mixed>>  $rules
     */
    protected function validateAnswers(array $rules): void
    {
        try {
            $this->validate($rules, $this->definition()->messages());
        } catch (ValidationException $exception) {
            $this->dispatch('supplier-form-invalid');

            throw $exception;
        }
    }
}
