<?php

namespace App\SupplierForms;

enum FieldType: string
{
    case Text = 'text';
    case Textarea = 'textarea';
    case Url = 'url';
    case Email = 'email';
    case Phone = 'phone';
    case Number = 'number';
    case Date = 'date';
    case Country = 'country';
    case Countries = 'countries';
    case Choice = 'choice';
    case Choices = 'choices';
    case Consent = 'consent';
    case Files = 'files';

    /**
     * Types whose answer is a list of values.
     */
    public function isMultiple(): bool
    {
        return in_array($this, [self::Countries, self::Choices, self::Files], true);
    }
}
