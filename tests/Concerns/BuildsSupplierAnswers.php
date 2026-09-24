<?php

namespace Tests\Concerns;

use App\Enums\SupplierApplicationStatus;
use App\Models\SupplierApplication;

trait BuildsSupplierAnswers
{
    /**
     * Valid answers of form 1 (contact).
     *
     * @return array<string, mixed>
     */
    protected function validContactAnswers(array $overrides = []): array
    {
        return [
            'company_name' => 'Kehrtechnik GmbH',
            'country' => 'DE',
            'city' => 'Hamburg',
            'website' => 'www.kehrtechnik.example',
            'company_age' => 'AGE_GT_10',
            'sales_languages' => ['LANG_DE', 'LANG_EN'],
            'equipment_categories' => ['EQ_ROAD_SWEEPER', 'EQ_SNOW'],
            'supplier_type' => 'MANUFACTURER',
            'flagship_product' => 'Street sweeper KT-500',
            'sectors' => ['SEC_AIRPORT', 'SEC_MUNICIPALITY'],
            'exports' => 'YES',
            'export_countries' => ['FR', 'AT'],
            'interest_level' => 'VERY_INTERESTED',
            'rfq_ready' => 'YES',
            'expectations' => 'Access to airports in France',
            'contact_name' => 'Hans Muster',
            'contact_role' => 'SALES_EXPORT_MANAGER',
            'contact_email' => 'hans@kehrtechnik.example',
            'contact_phone' => '+33612345678',
            'preferred_channel' => 'EMAIL',
            'preferred_language' => 'LANG_DE',
            'source' => 'LINKEDIN',
            'consent_contact' => true,
            ...$overrides,
        ];
    }

    /**
     * Answers of form 2 that are not pre-filled from form 1.
     *
     * @return array<string, mixed>
     */
    protected function validOnboardingOnlyAnswers(): array
    {
        return [
            'legal_form' => 'GmbH',
            'registration_number' => 'DE123456789',
            'founding_date' => '2004-05-01',
            'manufacturing_countries' => ['DE'],
            'hq_address' => 'Hafenstraße 1, 20457 Hamburg',
            'headcount' => 'HEADCOUNT_51_200',
            'main_products' => 'Sweepers, snow ploughs',
            'technical_specs' => 'Diesel and electric models',
            'customization' => 'DEPENDS_ON_MODEL',
            'production_mode' => 'BOTH',
            'certifications' => ['CERT_CE', 'CERT_ISO_9001'],
            'warranty_duration' => 'WARRANTY_2Y',
            'after_sales' => 'YES_PARTNERS',
            'spare_parts' => 'YES',
            'remote_support' => 'YES',
            'service_network' => 'NO',
            'major_clients' => 'YES',
            'references_available' => 'YES',
            'lead_time' => 'LEAD_TIME_1_3M',
            'production_ownership' => 'IN_HOUSE',
            'pricing_model' => 'CUSTOM_QUOTE',
            'currency' => 'EUR',
            'payment_methods' => ['BANK_TRANSFER'],
            'payment_terms' => 'NET_30',
            'volume_discounts' => 'YES_TIERS',
            'quote_response_time' => 'DAYS_1_3',
            'incoterms' => ['EXW', 'DAP'],
            'transport_responsibility' => 'DEPENDS_ON_AGREEMENT',
            'collaboration_type' => ['QUOTE_REQUESTS', 'NEW_MARKETS'],
            'b2b_display_consent' => 'YES',
            'price_display' => 'QUOTE_ONLY',
            'partnership_terms_ack' => true,
            'non_circumvention' => true,
            'consent_onboarding' => true,
        ];
    }

    protected function approvedApplication(array $attributes = []): SupplierApplication
    {
        $answers = $this->validContactAnswers();

        $application = SupplierApplication::create([
            'status' => SupplierApplicationStatus::New,
            'company_name' => $answers['company_name'],
            'country' => $answers['country'],
            'contact_name' => $answers['contact_name'],
            'contact_email' => $answers['contact_email'],
            'contact_phone' => $answers['contact_phone'],
            'contact_answers' => $answers,
            'contact_locale' => 'de',
            'contact_submitted_at' => now(),
            ...$attributes,
        ]);

        $application->approve();

        return $application->refresh();
    }
}
