<?php

namespace App\SupplierForms;

/**
 * One question of a supplier form. Its key is the technical key of the specification:
 * it is used for storage, validation, back-office display and export columns.
 */
final readonly class Question
{
    /**
     * @param  array{0: string, 1: array<int, string>}|null  $visibleWhen  [question key, answers showing this question]
     */
    public function __construct(
        public string $key,
        public FieldType $type,
        public bool $required = true,
        public ?string $list = null,
        public ?array $visibleWhen = null,
        public ?string $prefillFrom = null,
        public bool $hasHelp = false,
        public ?string $linkRoute = null,
        public bool $wide = true,
    ) {}

    public static function make(string $key, FieldType $type): self
    {
        return new self($key, $type);
    }

    public function optional(): self
    {
        return $this->with(['required' => false]);
    }

    public function list(string $list): self
    {
        return $this->with(['list' => $list]);
    }

    /**
     * @param  array<int, string>  $values
     */
    public function visibleWhen(string $key, array $values): self
    {
        return $this->with(['visibleWhen' => [$key, $values]]);
    }

    public function prefillFrom(string $key): self
    {
        return $this->with(['prefillFrom' => $key]);
    }

    public function withHelp(): self
    {
        return $this->with(['hasHelp' => true]);
    }

    /**
     * Route of the page linked from the label (consent checkboxes).
     */
    public function linkTo(string $route): self
    {
        return $this->with(['linkRoute' => $route]);
    }

    /**
     * Displayed on half a line on large screens.
     */
    public function half(): self
    {
        return $this->with(['wide' => false]);
    }

    /**
     * Answer key of the free text attached to an option ("Other", "please specify").
     */
    public function detailKey(string $optionKey): string
    {
        return $this->key.'_'.strtolower($optionKey);
    }

    /**
     * @param  array<string, mixed>  $changes
     */
    private function with(array $changes): self
    {
        return new self(...[...get_object_vars($this), ...$changes]);
    }
}
