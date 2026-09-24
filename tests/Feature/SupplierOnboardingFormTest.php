<?php

namespace Tests\Feature;

use App\Enums\SupplierApplicationStatus;
use App\Livewire\SupplierOnboardingForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\Concerns\BuildsSupplierAnswers;
use Tests\TestCase;

class SupplierOnboardingFormTest extends TestCase
{
    use BuildsSupplierAnswers;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');
        $this->seed();
    }

    public function test_the_private_link_opens_the_form_prefilled_with_form_1(): void
    {
        $application = $this->approvedApplication();

        $this->get($application->onboardingUrl())
            ->assertOk()
            ->assertSee('<html lang="de">', false)
            ->assertSee('Schritt 1 von 10')
            ->assertSee('Kehrtechnik GmbH');

        Livewire::test(SupplierOnboardingForm::class, ['token' => $application->onboarding_token])
            ->assertSet('answers.legal_name', 'Kehrtechnik GmbH')
            ->assertSet('answers.hq_country', 'DE')
            ->assertSet('answers.contact_role', 'SALES_EXPORT_MANAGER')
            ->assertSet('answers.equipment_categories', ['EQ_ROAD_SWEEPER', 'EQ_SNOW'])
            ->assertSet('answers.export_countries', ['FR', 'AT']);
    }

    public function test_invalid_expired_or_revoked_links_are_refused(): void
    {
        $this->get('/fr/integration-fournisseur/'.str_repeat('a', 64))->assertOk()->assertSee('Lien invalide ou expiré');

        $expired = $this->approvedApplication();
        $expired->update(['onboarding_token_expires_at' => now()->subDay()]);
        $this->get($expired->onboardingUrl('fr'))->assertSee('Lien invalide ou expiré');

        $revoked = $this->approvedApplication();
        $url = $revoked->onboardingUrl('fr');
        $revoked->revokeOnboardingLink();
        $this->get($url)->assertSee('Lien invalide ou expiré');
    }

    public function test_each_step_is_validated_and_the_draft_is_saved(): void
    {
        $application = $this->approvedApplication();

        $component = Livewire::test(SupplierOnboardingForm::class, ['token' => $application->onboarding_token])
            ->call('next')
            ->assertHasErrors(['answers.legal_form' => 'required', 'answers.founding_date' => 'required'])
            ->assertHasNoErrors(['answers.main_products'])
            ->assertSet('step', 0);

        $component->set('answers.legal_form', 'GmbH');

        $this->assertSame('GmbH', $application->refresh()->onboarding_answers['legal_form']);
        $this->assertNotNull($application->onboarding_saved_at);

        foreach (['registration_number', 'founding_date', 'manufacturing_countries', 'hq_address', 'headcount'] as $key) {
            $component->set("answers.{$key}", $this->validOnboardingOnlyAnswers()[$key]);
        }

        $component->call('next')->assertHasNoErrors()->assertSet('step', 1)->assertSet('furthestStep', 1);

        // Reopening the link resumes the draft.
        Livewire::test(SupplierOnboardingForm::class, ['token' => $application->onboarding_token])
            ->assertSet('answers.registration_number', 'DE123456789');
    }

    public function test_none_is_exclusive_among_certifications(): void
    {
        $application = $this->approvedApplication();

        Livewire::test(SupplierOnboardingForm::class, ['token' => $application->onboarding_token])
            ->set('answers.certifications', ['CERT_CE', 'CERT_ISO_9001'])
            ->set('answers.certifications', ['CERT_CE', 'CERT_ISO_9001', 'CERT_NONE'])
            ->assertSet('answers.certifications', ['CERT_NONE'])
            ->set('answers.certifications', ['CERT_NONE', 'CERT_ISO_14001'])
            ->assertSet('answers.certifications', ['CERT_ISO_14001']);
    }

    public function test_documents_are_stored_privately_and_downloadable_from_the_back_office_only(): void
    {
        $application = $this->approvedApplication();

        Livewire::test(SupplierOnboardingForm::class, ['token' => $application->onboarding_token])
            ->set('step', 8)
            ->set('uploads.doc_price_list', [UploadedFile::fake()->create('tarifs-2026.pdf', 200, 'application/pdf')])
            ->assertHasNoErrors()
            ->assertSee('tarifs-2026.pdf');

        $file = $application->refresh()->onboarding_answers['doc_price_list'][0];
        Storage::disk('local')->assertExists($file['path']);
        $this->assertStringStartsWith("supplier-documents/{$application->id}/doc_price_list/", $file['path']);

        $url = route('supplier-documents.download', ['supplierApplication' => $application, 'field' => 'doc_price_list', 'index' => 0]);

        $this->get($url)->assertForbidden();
        $this->actingAs(User::factory()->create())->get($url)->assertOk()->assertDownload('tarifs-2026.pdf');
    }

    public function test_documents_of_other_formats_are_refused(): void
    {
        $application = $this->approvedApplication();

        Livewire::test(SupplierOnboardingForm::class, ['token' => $application->onboarding_token])
            ->set('uploads.doc_photos', [UploadedFile::fake()->create('script.exe', 10)])
            ->assertHasErrors('uploads.doc_photos.0');

        $this->assertSame([], $application->refresh()->onboarding_answers['doc_photos'] ?? []);
    }

    public function test_the_completed_file_is_submitted_and_locked(): void
    {
        $application = $this->approvedApplication();

        $component = Livewire::test(SupplierOnboardingForm::class, ['token' => $application->onboarding_token]);

        foreach ($this->validOnboardingOnlyAnswers() as $key => $value) {
            $component->set("answers.{$key}", $value);
        }

        $component->set('step', 9)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true)
            ->assertSee("L'équipe Sabonea étudiera votre dossier", false);

        $application->refresh();
        $this->assertSame(SupplierApplicationStatus::OnboardingSubmitted, $application->status);
        $this->assertNotNull($application->onboarding_submitted_at);
        $this->assertSame('Kehrtechnik GmbH', $application->onboarding_answers['legal_name']);
        $this->assertArrayNotHasKey('service_network_countries', $application->onboarding_answers);

        // Once submitted, the link only shows the thank-you message.
        Livewire::test(SupplierOnboardingForm::class, ['token' => $application->onboarding_token])
            ->assertSet('submitted', true)
            ->set('answers.legal_form', 'AG');

        $this->assertSame('GmbH', $application->refresh()->onboarding_answers['legal_form']);
    }

    public function test_submitting_with_a_missing_answer_goes_back_to_its_step(): void
    {
        $application = $this->approvedApplication();

        $answers = $this->validOnboardingOnlyAnswers();
        unset($answers['lead_time']);

        $component = Livewire::test(SupplierOnboardingForm::class, ['token' => $application->onboarding_token]);

        foreach ($answers as $key => $value) {
            $component->set("answers.{$key}", $value);
        }

        $component->set('step', 9)
            ->call('submit')
            ->assertHasErrors(['answers.lead_time' => 'required'])
            ->assertSet('step', 5);
    }
}
