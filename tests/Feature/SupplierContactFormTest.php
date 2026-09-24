<?php

namespace Tests\Feature;

use App\Enums\SupplierApplicationStatus;
use App\Livewire\SupplierContactForm;
use App\Models\Setting;
use App\Models\SupplierApplication;
use App\Models\User;
use App\Support\SupplierForms;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\Concerns\BuildsSupplierAnswers;
use Tests\TestCase;

class SupplierContactFormTest extends TestCase
{
    use BuildsSupplierAnswers;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
        Setting::put(SupplierForms::PUBLIC_SETTING, '1');
    }

    /**
     * @param  array<string, mixed>  $answers
     */
    private function fill(array $answers): Testable
    {
        $component = Livewire::test(SupplierContactForm::class);

        foreach ($answers as $key => $value) {
            $component->set("answers.{$key}", $value);
        }

        return $component;
    }

    public function test_the_form_is_displayed_in_every_language(): void
    {
        $this->get('/fr/devenir-fournisseur')->assertOk()->assertSee('Nom de l&#039;entreprise', false)->assertSeeLivewire(SupplierContactForm::class);
        $this->get('/en/devenir-fournisseur')->assertOk()->assertSee('Company name');
        $this->get('/de/devenir-fournisseur')->assertOk()->assertSee('Name des Unternehmens');
        $this->get('/zh/devenir-fournisseur')->assertOk()->assertSee('公司名称');
    }

    public function test_a_closed_form_is_only_shown_to_back_office_users(): void
    {
        Setting::put(SupplierForms::PUBLIC_SETTING, '0');

        $this->get('/fr/devenir-fournisseur')->assertOk()->assertSee('Formulaire bientôt disponible')->assertDontSeeLivewire(SupplierContactForm::class);

        $this->actingAs(User::factory()->create())
            ->get('/fr/devenir-fournisseur')
            ->assertSeeLivewire(SupplierContactForm::class)
            ->assertSee('Mode test');
    }

    public function test_answers_are_stored_as_technical_keys(): void
    {
        $this->fill($this->validContactAnswers())
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true)
            ->assertSee('Notre équipe vous recontactera sous 48 h');

        $application = SupplierApplication::query()->sole();

        $this->assertSame(SupplierApplicationStatus::New, $application->status);
        $this->assertFalse($application->is_test);
        $this->assertSame('Kehrtechnik GmbH', $application->company_name);
        $this->assertSame('DE', $application->country);
        $this->assertSame(['EQ_ROAD_SWEEPER', 'EQ_SNOW'], $application->contact_answers['equipment_categories']);
        $this->assertSame(['FR', 'AT'], $application->contact_answers['export_countries']);
        $this->assertSame('https://www.kehrtechnik.example', $application->contact_answers['website']);
        $this->assertSame('fr', $application->contact_locale);
    }

    public function test_submissions_of_back_office_users_are_tests(): void
    {
        $this->actingAs(User::factory()->create());

        $this->fill($this->validContactAnswers())->call('submit')->assertHasNoErrors();

        $this->assertTrue(SupplierApplication::query()->sole()->is_test);
    }

    public function test_required_answers_are_checked(): void
    {
        Livewire::test(SupplierContactForm::class)
            ->call('submit')
            ->assertHasErrors([
                'answers.company_name' => 'required',
                'answers.country' => 'required',
                'answers.sales_languages' => 'required',
                'answers.equipment_categories' => 'required',
                'answers.consent_contact' => 'accepted',
            ])
            ->assertSet('submitted', false);

        $this->assertDatabaseCount('supplier_applications', 0);
    }

    public function test_export_countries_are_only_asked_to_exporters(): void
    {
        $this->fill($this->validContactAnswers(['exports' => 'NO', 'export_countries' => []]))
            ->assertDontSee('Vers quels pays exportez-vous')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertArrayNotHasKey('export_countries', SupplierApplication::query()->sole()->contact_answers);

        $this->fill($this->validContactAnswers(['exports' => 'OCCASIONALLY', 'export_countries' => []]))
            ->assertSee('Vers quels pays exportez-vous')
            ->call('submit')
            ->assertHasErrors(['answers.export_countries' => 'required']);
    }

    public function test_other_options_require_a_free_text(): void
    {
        $component = $this->fill($this->validContactAnswers(['sales_languages' => ['LANG_EN', 'LANG_OTHER']]))
            ->call('submit')
            ->assertHasErrors(['answers.sales_languages_lang_other' => 'required']);

        $component->set('answers.sales_languages_lang_other', 'Italien')->call('submit')->assertHasNoErrors();

        $this->assertSame('Italien', SupplierApplication::query()->sole()->contact_answers['sales_languages_lang_other']);
    }

    public function test_countries_can_be_added_and_removed(): void
    {
        Livewire::test(SupplierContactForm::class)
            ->call('addCountry', 'export_countries', 'CN')
            ->call('addCountry', 'export_countries', 'US')
            ->call('addCountry', 'export_countries', 'CN')
            ->call('addCountry', 'export_countries', 'XX')
            ->assertSet('answers.export_countries', ['CN', 'US'])
            ->call('removeCountry', 'export_countries', 'CN')
            ->assertSet('answers.export_countries', ['US']);
    }

    public function test_disposable_emails_and_invalid_phones_are_refused(): void
    {
        $this->fill($this->validContactAnswers(['contact_email' => 'test@yopmail.com', 'contact_phone' => '12345']))
            ->call('submit')
            ->assertHasErrors(['answers.contact_email', 'answers.contact_phone' => 'phone']);
    }

    public function test_messages_follow_the_language_of_the_page(): void
    {
        app()->setLocale('de');

        Livewire::test(SupplierContactForm::class)
            ->call('submit')
            ->assertSee('Dieses Feld ist erforderlich.');
    }
}
