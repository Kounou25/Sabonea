<?php

namespace App\SupplierForms;

/**
 * Form 2 — supplier onboarding: private link, long, pre-filled with the answers of form 1.
 */
class SupplierOnboardingForm extends SupplierForm
{
    /**
     * @var array<int, string>
     */
    public const DOCUMENT_KEYS = ['doc_price_list', 'doc_datasheets', 'doc_photos', 'doc_brochures', 'doc_certificates'];

    public function uiPrefix(): string
    {
        return 'supplier_onboarding';
    }

    public function sections(): array
    {
        return [
            new FormSection('identity', [
                Question::make('legal_name', FieldType::Text)->prefillFrom('company_name')->half(),
                Question::make('legal_form', FieldType::Text)->withHelp()->half(),
                Question::make('registration_number', FieldType::Text)->withHelp(),
                Question::make('founding_date', FieldType::Date)->half(),
                Question::make('hq_country', FieldType::Country)->prefillFrom('country')->half(),
                Question::make('manufacturing_countries', FieldType::Countries),
                Question::make('hq_address', FieldType::Textarea),
                Question::make('website', FieldType::Url)->optional()->prefillFrom('website'),
                Question::make('headcount', FieldType::Choice)->list('headcount'),
                Question::make('annual_revenue', FieldType::Choice)->list('annual_revenue')->optional(),
                Question::make('contact_name', FieldType::Text)->prefillFrom('contact_name'),
                Question::make('contact_email', FieldType::Email)->prefillFrom('contact_email')->half(),
                Question::make('contact_phone', FieldType::Phone)->prefillFrom('contact_phone')->half(),
                Question::make('contact_role', FieldType::Choice)->list('contact_role')->prefillFrom('contact_role'),
                Question::make('sales_languages', FieldType::Choices)->list('sales_languages')->prefillFrom('sales_languages'),
            ], 'fa fa-building'),
            new FormSection('products', [
                Question::make('equipment_categories', FieldType::Choices)->list(OptionLists::EQUIPMENT)->prefillFrom('equipment_categories'),
                Question::make('supplier_type', FieldType::Choice)->list('supplier_type')->prefillFrom('supplier_type'),
                Question::make('product_count', FieldType::Number)->optional()->half(),
                Question::make('main_products', FieldType::Textarea),
                Question::make('technical_specs', FieldType::Textarea),
                Question::make('customization', FieldType::Choice)->list('customization'),
                Question::make('production_mode', FieldType::Choice)->list('production_mode'),
            ], 'fa fa-cogs'),
            new FormSection('certifications', [
                Question::make('certifications', FieldType::Choices)->list('certifications'),
                Question::make('standards', FieldType::Textarea)->optional(),
            ], 'fa fa-certificate'),
            new FormSection('after_sales', [
                Question::make('warranty_duration', FieldType::Choice)->list('warranty_duration'),
                Question::make('after_sales', FieldType::Choice)->list('after_sales'),
                Question::make('spare_parts', FieldType::Choice)->list('spare_parts'),
                Question::make('remote_support', FieldType::Choice)->list('remote_support'),
                Question::make('service_network', FieldType::Choice)->list('service_network'),
                Question::make('service_network_countries', FieldType::Countries)->visibleWhen('service_network', ['YES']),
            ], 'fa fa-tools'),
            new FormSection('markets', [
                Question::make('sectors', FieldType::Choices)->list(OptionLists::SECTORS)->prefillFrom('sectors'),
                Question::make('export_countries', FieldType::Countries)->optional()->prefillFrom('export_countries'),
                Question::make('major_clients', FieldType::Choice)->list('yes_no')->half(),
                Question::make('references_available', FieldType::Choice)->list('yes_no')->half(),
            ], 'fa fa-globe-europe'),
            new FormSection('production', [
                Question::make('annual_capacity', FieldType::Text)->optional(),
                Question::make('lead_time', FieldType::Choice)->list('lead_time'),
                Question::make('production_ownership', FieldType::Choice)->list('production_ownership'),
            ], 'fa fa-industry'),
            new FormSection('pricing', [
                Question::make('pricing_model', FieldType::Choice)->list('pricing_model'),
                Question::make('currency', FieldType::Choice)->list('currency'),
                Question::make('payment_methods', FieldType::Choices)->list('payment_methods'),
                Question::make('payment_terms', FieldType::Choice)->list('payment_terms'),
                Question::make('volume_discounts', FieldType::Choice)->list('volume_discounts'),
                Question::make('quote_response_time', FieldType::Choice)->list('quote_response_time'),
            ], 'fa fa-tags'),
            new FormSection('logistics', [
                Question::make('incoterms', FieldType::Choices)->list('incoterms'),
                Question::make('shipping_ports', FieldType::Textarea)->optional(),
                Question::make('transport_responsibility', FieldType::Choice)->list('transport_responsibility'),
            ], 'fa fa-shipping-fast'),
            new FormSection('documents', [
                ...array_map(fn (string $key): Question => Question::make($key, FieldType::Files)->optional(), self::DOCUMENT_KEYS),
                Question::make('demo_video_url', FieldType::Url)->optional()->withHelp(),
            ], 'fa fa-folder-open'),
            new FormSection('collaboration', [
                Question::make('collaboration_type', FieldType::Choices)->list('collaboration_type'),
                Question::make('expectations', FieldType::Textarea)->optional()->prefillFrom('expectations'),
                Question::make('b2b_display_consent', FieldType::Choice)->list('yes_no'),
                Question::make('price_display', FieldType::Choice)->list('price_display'),
                Question::make('special_conditions', FieldType::Textarea)->optional(),
                Question::make('partnership_terms_ack', FieldType::Consent)->linkTo('conditions-fournisseurs'),
                Question::make('non_circumvention', FieldType::Consent),
                Question::make('consent_onboarding', FieldType::Consent)->linkTo('politique-de-confidentialite'),
            ], 'fa fa-handshake'),
        ];
    }

    /**
     * Answers of form 2 pre-filled with those of form 1, so nothing is typed twice.
     *
     * @param  array<string, mixed>  $contactAnswers
     * @return array<string, mixed>
     */
    public function prefill(array $contactAnswers): array
    {
        $answers = $this->emptyAnswers();

        foreach ($this->questions() as $question) {
            if ($question->prefillFrom === null || ! array_key_exists($question->prefillFrom, $contactAnswers)) {
                continue;
            }

            $answers[$question->key] = $contactAnswers[$question->prefillFrom];

            // Free texts of "Other" options follow their question.
            foreach ($contactAnswers as $key => $value) {
                if (str_starts_with($key, $question->prefillFrom.'_') && $question->list !== null) {
                    $answers[$question->key.substr($key, strlen($question->prefillFrom))] = $value;
                }
            }
        }

        return $answers;
    }
}
