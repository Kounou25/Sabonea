<?php

namespace Tests\Feature;

use App\Enums\SupplierApplicationStatus;
use App\Filament\Resources\SupplierApplications\Pages\ViewSupplierApplication;
use App\Models\SupplierApplication;
use App\Models\User;
use App\SupplierForms\SupplierOnboardingForm;
use App\SupplierForms\SupplierProfileDocument;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\Concerns\BuildsSupplierAnswers;
use Tests\TestCase;

class SupplierProfilePdfTest extends TestCase
{
    use BuildsSupplierAnswers;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
        Filament::setCurrentPanel('admin');
    }

    private function onboardedApplication(): SupplierApplication
    {
        $application = $this->approvedApplication(['internal_notes' => 'Rencontré au salon Pollutec']);
        $answers = [
            ...(new SupplierOnboardingForm)->prefill($application->contact_answers),
            ...$this->validOnboardingOnlyAnswers(),
            'main_products' => '电动道路清扫车 KT-500',
            'doc_price_list' => [['path' => 'x', 'name' => 'tarifs-2026.pdf', 'size' => 812000]],
        ];

        $application->update([
            'status' => SupplierApplicationStatus::OnboardingSubmitted,
            'onboarding_answers' => $answers,
            'onboarding_submitted_at' => now(),
        ]);

        return $application;
    }

    public function test_the_pdf_is_only_available_to_back_office_users(): void
    {
        $application = $this->onboardedApplication();
        $url = route('supplier-applications.profile-pdf', ['supplierApplication' => $application]);

        $this->get($url)->assertForbidden();

        $response = $this->actingAs(User::factory()->create())->get($url);
        $response->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_the_internal_version_contains_contact_details_follow_up_and_notes(): void
    {
        $html = (new SupplierProfileDocument($this->onboardedApplication(), SupplierProfileDocument::INTERNAL, 'fr'))->html();

        $this->assertStringContainsString('Kehrtechnik GmbH', $html);
        $this->assertStringContainsString('hans@kehrtechnik.example', $html);
        $this->assertStringContainsString('+33612345678', $html);
        $this->assertStringContainsString('Rencontré au salon Pollutec', $html);
        $this->assertStringContainsString('tarifs-2026.pdf', $html);
        $this->assertStringContainsString('Balayeuses / nettoyeuses de voirie', $html);
        $this->assertStringContainsString('Non-contournement', $html);
    }

    public function test_the_buyer_version_never_shows_direct_contact_details(): void
    {
        $html = (new SupplierProfileDocument($this->onboardedApplication(), SupplierProfileDocument::BUYER, 'en'))->html();

        $this->assertStringContainsString('Kehrtechnik GmbH', $html);
        $this->assertStringContainsString('Road sweepers / street cleaners', $html);
        $this->assertStringContainsString('Available on request from Sabonea.', $html);
        $this->assertStringContainsString('contact@sabonea.com', $html);

        foreach (['hans@kehrtechnik.example', '+33612345678', 'Hans Muster', 'kehrtechnik.example', 'Hafenstraße', 'DE123456789', 'Pollutec', 'tarifs-2026.pdf'] as $private) {
            $this->assertStringNotContainsString($private, $html, "The buyer version must not show {$private}");
        }
    }

    public function test_the_document_is_written_in_the_chosen_language(): void
    {
        $html = (new SupplierProfileDocument($this->onboardedApplication(), SupplierProfileDocument::BUYER, 'zh'))->html();

        $this->assertStringContainsString('供应商档案', $html);
        $this->assertStringContainsString('道路清扫车 / 洗扫车', $html);
        $this->assertStringContainsString('德国', $html);
        $this->assertStringContainsString('电动道路清扫车 KT-500', $html);
    }

    public function test_option_labels_drop_the_filling_instructions_and_show_the_detail(): void
    {
        $form = new SupplierOnboardingForm;
        $answers = ['sales_languages_lang_other' => 'Polonais', 'certifications_cert_airport' => 'EASA', 'service_network_yes' => 'ignored'];

        $this->assertSame('Autre : Polonais', $form->optionWithDetail($form->question('sales_languages'), 'LANG_OTHER', $answers, 'fr'));
        $this->assertSame('Conformité aux exigences aéroportuaires : EASA', $form->optionWithDetail($form->question('certifications'), 'CERT_AIRPORT', $answers, 'fr'));
        $this->assertSame('符合机场要求 : EASA', $form->optionWithDetail($form->question('certifications'), 'CERT_AIRPORT', $answers, 'zh'));
        $this->assertSame('Yes', $form->optionWithDetail($form->question('service_network'), 'YES', $answers, 'en'));
        $this->assertSame('Ja', $form->optionWithDetail($form->question('service_network'), 'YES', $answers, 'de'));
        $this->assertSame('Anglais', $form->optionWithDetail($form->question('sales_languages'), 'LANG_EN', $answers, 'fr'));
    }

    public function test_the_pdf_can_be_previewed_and_downloaded_from_the_back_office(): void
    {
        $this->actingAs(User::factory()->create());
        $application = $this->onboardedApplication();

        Livewire::test(ViewSupplierApplication::class, ['record' => $application->getRouteKey()])
            ->mountAction('profilePdf')
            ->assertMountedActionModalSee(route('supplier-applications.profile-pdf', ['supplierApplication' => $application, 'audience' => 'internal', 'locale' => 'fr']))
            ->setActionData(['audience' => SupplierProfileDocument::BUYER, 'locale' => 'de'])
            ->assertMountedActionModalSee(route('supplier-applications.profile-pdf', ['supplierApplication' => $application, 'audience' => 'buyer', 'locale' => 'de']))
            ->callMountedAction()
            ->assertFileDownloaded('fiche-fournisseur-kehrtechnik-gmbh-acheteur-de.pdf');
    }
}
