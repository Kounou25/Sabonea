<?php

namespace App\SupplierForms;

final readonly class FormSection
{
    /**
     * @param  array<int, Question>  $questions
     * @param  string  $icon  Font Awesome 5 class shown next to the section title
     */
    public function __construct(
        public string $key,
        public array $questions,
        public string $icon = 'fa fa-pen',
    ) {}
}
