<?php

namespace App\SupplierForms;

use App\Rules\NotDisposableEmail;
use App\Support\Countries;
use App\Support\Ui;
use Closure;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * A supplier form described once: rendering, validation, back-office display and export use this definition.
 */
abstract class SupplierForm
{
    /**
     * Prefix of the interface strings of the form (questions, help texts, section titles).
     */
    abstract public function uiPrefix(): string;

    /**
     * @return array<int, FormSection>
     */
    abstract public function sections(): array;

    /**
     * @return array<int, Question>
     */
    public function questions(): array
    {
        return array_merge(...array_map(fn (FormSection $section): array => $section->questions, $this->sections()));
    }

    public function question(string $key): ?Question
    {
        foreach ($this->questions() as $question) {
            if ($question->key === $key) {
                return $question;
            }
        }

        return null;
    }

    public function label(Question $question, ?string $locale = null): string
    {
        return Ui::get("{$this->uiPrefix()}.q.{$question->key}", [], $locale);
    }

    /**
     * Label without markdown links, for the back-office and exports.
     */
    public function plainLabel(Question $question, ?string $locale = null): string
    {
        return preg_replace('/\[([^\]]+)\]\([^)]*\)/', '$1', $this->label($question, $locale));
    }

    public function help(Question $question, ?string $locale = null): ?string
    {
        return $question->hasHelp ? Ui::get("{$this->uiPrefix()}.help.{$question->key}", [], $locale) : null;
    }

    /**
     * Label of a checkbox, with the link to the page it refers to (privacy policy, partnership terms).
     */
    public function consentLabel(Question $question): HtmlString
    {
        return Ui::md("{$this->uiPrefix()}.q.{$question->key}", [
            'url' => $question->linkRoute ? route($question->linkRoute, ['locale' => app()->getLocale()]) : '#',
        ]);
    }

    public function sectionTitle(FormSection $section, ?string $locale = null): string
    {
        return Ui::get("{$this->uiPrefix()}.section.{$section->key}", [], $locale);
    }

    /**
     * @param  array<string, mixed>  $answers
     */
    public function isVisible(Question $question, array $answers): bool
    {
        if ($question->visibleWhen === null) {
            return true;
        }

        [$key, $values] = $question->visibleWhen;
        $answer = $answers[$key] ?? null;

        return is_array($answer) ? array_intersect($answer, $values) !== [] : in_array($answer, $values, true);
    }

    /**
     * Free texts currently expected for a question: one per selected "Other / please specify" option.
     *
     * @param  array<string, mixed>  $answers
     * @return array<string, string> option key => answer key of the free text
     */
    public function activeDetails(Question $question, array $answers): array
    {
        if ($question->list === null) {
            return [];
        }

        $selected = (array) ($answers[$question->key] ?? []);
        $details = [];

        foreach (OptionLists::otherKeys($question->list) as $optionKey) {
            if (in_array($optionKey, $selected, true)) {
                $details[$optionKey] = $question->detailKey($optionKey);
            }
        }

        return $details;
    }

    public function isAnswered(Question $question, array $answers): bool
    {
        $value = $answers[$question->key] ?? null;

        return match (true) {
            $question->type->isMultiple() => ! empty($value),
            $question->type === FieldType::Consent => (bool) $value,
            default => filled($value),
        };
    }

    /**
     * Required visible questions answered, per section (for the progress display).
     *
     * @param  array<string, mixed>  $answers
     * @return array<string, array{answered: int, total: int, complete: bool}>
     */
    public function progress(array $answers): array
    {
        $progress = [];

        foreach ($this->sections() as $section) {
            $required = array_filter(
                $section->questions,
                fn (Question $question): bool => $question->required && $this->isVisible($question, $answers),
            );
            $answered = count(array_filter($required, fn (Question $question): bool => $this->isAnswered($question, $answers)));

            $progress[$section->key] = [
                'answered' => $answered,
                'total' => count($required),
                'complete' => $answered === count($required),
            ];
        }

        return $progress;
    }

    /**
     * Share of the required visible questions answered, from 0 to 100.
     *
     * @param  array<string, array{answered: int, total: int, complete: bool}>  $progress
     */
    public function progressPercent(array $progress): int
    {
        $total = array_sum(array_column($progress, 'total'));

        return $total === 0 ? 100 : (int) round(array_sum(array_column($progress, 'answered')) / $total * 100);
    }

    /**
     * Empty answers: lists for multiple choices, false for checkboxes.
     *
     * @return array<string, mixed>
     */
    public function emptyAnswers(): array
    {
        $answers = [];

        foreach ($this->questions() as $question) {
            $answers[$question->key] = match (true) {
                $question->type->isMultiple() => [],
                $question->type === FieldType::Consent => false,
                default => null,
            };
        }

        return $answers;
    }

    /**
     * Fixes answers that are valid once completed (web addresses typed without https://).
     *
     * @param  array<string, mixed>  $answers
     * @return array<string, mixed>
     */
    public function prepare(array $answers): array
    {
        foreach ($this->questions() as $question) {
            $value = $answers[$question->key] ?? null;

            if ($question->type === FieldType::Url && is_string($value) && filled($value) && ! preg_match('#^https?://#i', trim($value))) {
                $answers[$question->key] = 'https://'.trim($value);
            }
        }

        return $answers;
    }

    /**
     * Validation rules of the visible questions (of one section, or of the whole form).
     *
     * @param  array<string, mixed>  $answers
     * @return array<string, array<int, mixed>>
     */
    public function rules(array $answers, ?FormSection $section = null): array
    {
        $rules = [];

        foreach ($section?->questions ?? $this->questions() as $question) {
            if (! $this->isVisible($question, $answers)) {
                continue;
            }

            $path = "answers.{$question->key}";
            $rules[$path] = [$question->required ? 'required' : 'nullable', ...$this->valueRules($question)];

            if ($question->type === FieldType::Consent && $question->required) {
                $rules[$path] = ['accepted'];
            }

            if ($itemRules = $this->itemRules($question)) {
                $rules["{$path}.*"] = $itemRules;
            }

            foreach ($this->activeDetails($question, $answers) as $detailKey) {
                $rules["answers.{$detailKey}"] = ['required', 'string', 'max:255'];
            }
        }

        return $rules;
    }

    /**
     * Error messages, in the visitor's language.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => Ui::get('form.error_required'),
            'accepted' => Ui::get('form.error_consent'),
            'email' => Ui::get('form.error_email'),
            'url' => Ui::get('form.error_url'),
            'max' => Ui::get('form.error_max'),
            'in' => Ui::get('form.error_invalid'),
            'array' => Ui::get('form.error_invalid'),
            'distinct' => Ui::get('form.error_invalid'),
            'date' => Ui::get('form.error_date'),
            'before_or_equal' => Ui::get('form.error_date'),
            'after' => Ui::get('form.error_date'),
            'integer' => Ui::get('form.error_number'),
            'min' => Ui::get('form.error_number'),
            'phone' => Ui::get('form.error_phone'),
        ];
    }

    /**
     * Answers as stored: hidden questions dropped, texts trimmed, lists re-indexed.
     *
     * @param  array<string, mixed>  $answers
     * @return array<string, mixed>
     */
    public function normalize(array $answers): array
    {
        $answers = $this->prepare($answers);
        $normalized = [];

        foreach ($this->questions() as $question) {
            if (! $this->isVisible($question, $answers)) {
                continue;
            }

            $value = $answers[$question->key] ?? null;

            $normalized[$question->key] = match (true) {
                $question->type === FieldType::Files => array_values((array) $value),
                $question->type->isMultiple() => array_values(array_filter((array) $value, fn ($item): bool => filled($item))),
                $question->type === FieldType::Consent => (bool) $value,
                $question->type === FieldType::Number => filled($value) ? (int) $value : null,
                default => is_string($value) && trim($value) !== '' ? trim($value) : null,
            };

            foreach ($this->activeDetails($question, $answers) as $detailKey) {
                $detail = $answers[$detailKey] ?? null;
                $normalized[$detailKey] = is_string($detail) && trim($detail) !== '' ? trim($detail) : null;
            }
        }

        return $normalized;
    }

    /**
     * Export columns: one per technical key, plus one per free text of "Other" options.
     *
     * @return array<int, string>
     */
    public function exportColumns(): array
    {
        $columns = [];

        foreach ($this->questions() as $question) {
            $columns[] = $question->key;

            if ($question->list !== null) {
                foreach (OptionLists::otherKeys($question->list) as $optionKey) {
                    $columns[] = $question->detailKey($optionKey);
                }
            }
        }

        return $columns;
    }

    /**
     * Stored value of a column, as exported: keys (not translated labels), lists separated by "|".
     *
     * @param  array<string, mixed>  $answers
     */
    public function exportValue(string $column, array $answers): string|int|null
    {
        $value = $answers[$column] ?? null;
        $question = $this->question($column);

        return match (true) {
            $question?->type === FieldType::Files => collect((array) $value)->pluck('name')->implode(' | '),
            is_array($value) => implode('|', $value),
            is_bool($value) => $value ? 1 : 0,
            default => $value,
        };
    }

    /**
     * Readable answer (labels instead of keys), for the back-office.
     *
     * @param  array<string, mixed>  $answers
     */
    public function displayValue(Question $question, array $answers, ?string $locale = null): ?string
    {
        $value = $answers[$question->key] ?? null;

        if (in_array($question->type, [FieldType::Choice, FieldType::Choices], true)) {
            $labels = array_map(
                fn (string $key): string => $this->optionWithDetail($question, $key, $answers, $locale),
                array_values(array_filter((array) $value, fn ($key): bool => filled($key))),
            );

            return $labels === [] ? null : implode(', ', $labels);
        }

        $display = match ($question->type) {
            FieldType::Country => Countries::name($value, $locale),
            FieldType::Countries => Countries::names($value, $locale),
            FieldType::Consent => $value ? '✔' : '✘',
            FieldType::Files => collect((array) $value)->pluck('name')->implode(', '),
            default => is_scalar($value) ? (string) $value : null,
        };

        return filled($display) ? $display : null;
    }

    /**
     * Label of a selected option, followed by the free text given for it ("Autre : Polonais").
     *
     * @param  array<string, mixed>  $answers
     */
    public function optionWithDetail(Question $question, string $optionKey, array $answers, ?string $locale = null): string
    {
        // "(préciser)", "(please specify)", "(bitte Länder angeben)"... only makes sense while filling in the form.
        $label = preg_replace(
            '/\s*[(（][^()（）]*(préciser|please|bitte|请)[^()（）]*[)）]\s*$/iu',
            '',
            (string) OptionLists::label($question->list, $optionKey, $locale),
        );
        $detail = $answers[$question->detailKey($optionKey)] ?? null;

        if (blank($detail) || ! in_array($optionKey, OptionLists::otherKeys($question->list), true)) {
            return $label;
        }

        return "{$label} : {$detail}";
    }

    /**
     * @return array<int, mixed>
     */
    private function valueRules(Question $question): array
    {
        return match ($question->type) {
            FieldType::Text => ['string', 'max:255'],
            FieldType::Textarea => ['string', 'max:5000'],
            FieldType::Url => ['url:http,https', 'max:255'],
            FieldType::Email => ['email:rfc', 'max:255', new NotDisposableEmail],
            FieldType::Phone => ['string', 'phone:INTERNATIONAL'],
            FieldType::Number => ['integer', 'min:0', 'max:1000000000'],
            FieldType::Date => ['date', 'before_or_equal:today', 'after:1800-01-01'],
            FieldType::Country => [Rule::in(Countries::CODES)],
            FieldType::Countries => ['array'],
            FieldType::Choice => [Rule::in(OptionLists::activeKeys($question->list))],
            FieldType::Choices => ['array', $this->exclusiveRule($question->list)],
            FieldType::Consent => ['boolean'],
            FieldType::Files => ['array', 'max:10'],
        };
    }

    /**
     * @return array<int, mixed>
     */
    private function itemRules(Question $question): array
    {
        return match ($question->type) {
            FieldType::Countries => ['distinct', Rule::in(Countries::CODES)],
            FieldType::Choices => ['distinct', Rule::in(OptionLists::activeKeys($question->list))],
            default => [],
        };
    }

    /**
     * "None" cannot be combined with another option.
     */
    private function exclusiveRule(string $list): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) use ($list): void {
            $exclusive = OptionLists::exclusiveKeys($list);

            if (is_array($value) && count($value) > 1 && array_intersect($value, $exclusive) !== []) {
                $fail(Ui::get('form.error_exclusive', ['option' => Str::lower(OptionLists::label($list, $exclusive[0]) ?? '')]));
            }
        };
    }
}
