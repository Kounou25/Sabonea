<?php

namespace App\SupplierForms;

/**
 * Form 1 — supplier contact ("Become a supplier"): public, short, qualifies the supplier.
 */
class SupplierContactForm extends SupplierForm
{
    public function uiPrefix(): string
    {
        return 'supplier_contact';
    }

    public function sections(): array
    {
        return [
            new FormSection('identity', [
                Question::make('company_name', FieldType::Text)->half(),
                Question::make('country', FieldType::Country)->half(),
                Question::make('city', FieldType::Text)->half(),
                Question::make('website', FieldType::Url)->optional()->half(),
                Question::make('company_age', FieldType::Choice)->list('company_age'),
                Question::make('sales_languages', FieldType::Choices)->list('sales_languages'),
            ], 'fa fa-building'),
            new FormSection('products', [
                Question::make('equipment_categories', FieldType::Choices)->list(OptionLists::EQUIPMENT),
                Question::make('supplier_type', FieldType::Choice)->list('supplier_type'),
                Question::make('flagship_product', FieldType::Text),
            ], 'fa fa-cogs'),
            new FormSection('markets', [
                Question::make('sectors', FieldType::Choices)->list(OptionLists::SECTORS),
                Question::make('exports', FieldType::Choice)->list('exports'),
                Question::make('export_countries', FieldType::Countries)->visibleWhen('exports', ['YES', 'OCCASIONALLY']),
            ], 'fa fa-globe-europe'),
            new FormSection('interest', [
                Question::make('interest_level', FieldType::Choice)->list('interest_level'),
                Question::make('rfq_ready', FieldType::Choice)->list('rfq_ready'),
                Question::make('expectations', FieldType::Textarea)->optional(),
            ], 'fa fa-handshake'),
            new FormSection('contact', [
                Question::make('contact_name', FieldType::Text),
                Question::make('contact_email', FieldType::Email)->half(),
                Question::make('contact_phone', FieldType::Phone)->half(),
                Question::make('contact_role', FieldType::Choice)->list('contact_role'),
                Question::make('preferred_channel', FieldType::Choice)->list('preferred_channel'),
                Question::make('preferred_language', FieldType::Choice)->list('preferred_language'),
                Question::make('source', FieldType::Choice)->list('source')->optional(),
                Question::make('consent_contact', FieldType::Consent)->linkTo('politique-de-confidentialite'),
            ], 'fa fa-user-tie'),
        ];
    }
}
