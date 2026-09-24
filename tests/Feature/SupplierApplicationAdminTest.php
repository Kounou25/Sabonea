<?php

namespace Tests\Feature;

use App\Enums\SupplierApplicationStatus;
use App\Filament\Resources\SupplierApplications\Pages\ListSupplierApplications;
use App\Filament\Resources\SupplierApplications\Pages\ViewSupplierApplication;
use App\Models\SupplierApplication;
use App\Models\User;
use App\SupplierForms\SupplierApplicationExporter;
use App\SupplierForms\SupplierContactForm;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\Concerns\BuildsSupplierAnswers;
use Tests\TestCase;

class SupplierApplicationAdminTest extends TestCase
{
    use BuildsSupplierAnswers;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
        Filament::setCurrentPanel('admin');
        $this->actingAs(User::factory()->create());
    }

    private function newApplication(bool $isTest = false): SupplierApplication
    {
        $answers = $this->validContactAnswers();

        return SupplierApplication::create([
            'status' => SupplierApplicationStatus::New,
            'is_test' => $isTest,
            'company_name' => $answers['company_name'],
            'country' => $answers['country'],
            'contact_answers' => $answers,
            'contact_locale' => 'en',
            'contact_submitted_at' => now(),
        ]);
    }

    public function test_applications_are_listed_and_displayed_in_french(): void
    {
        $application = $this->newApplication();

        $this->get('/admin/supplier-applications')->assertOk()->assertSee('Kehrtechnik GmbH')->assertSee('Allemagne');

        $this->get("/admin/supplier-applications/{$application->id}?tab=formulaire-1::data::tab")
            ->assertOk()
            ->assertSee('Balayeuses / nettoyeuses de voirie')
            ->assertSee('Autriche');
    }

    public function test_approving_creates_a_private_link_prefilled_from_form_1(): void
    {
        $application = $this->newApplication();

        Livewire::test(ViewSupplierApplication::class, ['record' => $application->getRouteKey()])
            ->callAction('approve')
            ->assertHasNoActionErrors();

        $application->refresh();
        $this->assertSame(SupplierApplicationStatus::Approved, $application->status);
        $this->assertSame(64, strlen($application->onboarding_token));
        $this->assertTrue($application->onboarding_token_expires_at->between(now()->addDays(29), now()->addDays(31)));
        $this->assertSame('Kehrtechnik GmbH', $application->onboarding_answers['legal_name']);
        $this->assertSame('de', $application->preferredLocale());
        $this->assertStringContainsString('/de/integration-fournisseur/', $application->onboardingUrl());
    }

    public function test_renewing_the_link_invalidates_the_old_one(): void
    {
        $application = $this->approvedApplication();
        $oldToken = $application->onboarding_token;

        Livewire::test(ViewSupplierApplication::class, ['record' => $application->getRouteKey()])
            ->callAction('renewLink');

        $this->assertNotSame($oldToken, $application->refresh()->onboarding_token);
        $this->assertNull(SupplierApplication::findByOnboardingToken($oldToken));
    }

    public function test_the_export_has_one_column_per_technical_key_and_skips_tests(): void
    {
        $this->newApplication();
        $this->newApplication(isTest: true);

        $rows = app(SupplierApplicationExporter::class)->rows(SupplierApplicationExporter::CONTACT, new SupplierContactForm);

        $header = $rows->first();
        $this->assertContains('equipment_categories', $header);
        $this->assertContains('sales_languages_lang_other', $header);
        $this->assertCount(2, $rows, 'Header and the only real application');

        $row = array_combine($header, $rows->last());
        $this->assertSame('EQ_ROAD_SWEEPER|EQ_SNOW', $row['equipment_categories']);
        $this->assertSame('DE', $row['country']);
        $this->assertSame(1, $row['consent_contact']);
    }

    public function test_the_export_can_be_downloaded(): void
    {
        $this->newApplication();

        Livewire::test(ListSupplierApplications::class)
            ->callAction('export_contact_xlsx')
            ->assertFileDownloaded('sabonea-contacts-fournisseurs-'.now()->format('Y-m-d').'.xlsx');
    }
}
