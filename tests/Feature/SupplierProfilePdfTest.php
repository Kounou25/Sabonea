<?php

namespace Tests\Feature;

use App\Enums\SupplierApplicationStatus;
use App\Filament\Resources\SupplierApplications\Pages\ViewSupplierApplication;
use App\Models\SupplierApplication;
use App\Models\User;
use App\Pdf\Fpdi;
use App\SupplierForms\SupplierOnboardingForm;
use App\SupplierForms\SupplierProfileDocument;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use setasign\Fpdi\PdfParser\StreamReader;
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
        Storage::fake('local');
        $this->seed();
        Filament::setCurrentPanel('admin');
    }

    /**
     * @param  array<string, mixed>  $answers
     */
    private function onboardedApplication(array $answers = []): SupplierApplication
    {
        $application = $this->approvedApplication(['internal_notes' => 'Rencontré au salon Pollutec']);

        $application->update([
            'status' => SupplierApplicationStatus::OnboardingSubmitted,
            'onboarding_answers' => [
                ...(new SupplierOnboardingForm)->prefill($application->contact_answers),
                ...$this->validOnboardingOnlyAnswers(),
                'main_products' => '电动道路清扫车 KT-500',
                'doc_price_list' => [['path' => 'x', 'name' => 'tarifs-2026.pdf', 'size' => 812000]],
                ...$answers,
            ],
            'onboarding_submitted_at' => now(),
        ]);

        return $application;
    }

    /**
     * A file stored like the uploads of form 2.
     *
     * @return array{path: string, name: string, size: int}
     */
    private function storedFile(string $field, string $name, string $content): array
    {
        $path = "supplier-documents/1/{$field}/".md5($name).'.'.pathinfo($name, PATHINFO_EXTENSION);
        Storage::disk('local')->put($path, $content);

        return ['path' => $path, 'name' => $name, 'size' => strlen($content)];
    }

    private function jpeg(int $width, int $height): string
    {
        $image = imagecreatetruecolor($width, $height);
        imagefill($image, 0, 0, (int) imagecolorallocate($image, 20, 90, 160));
        ob_start();
        imagejpeg($image);

        return (string) ob_get_clean();
    }

    private function applicationWithDocuments(): SupplierApplication
    {
        return $this->onboardedApplication([
            'doc_price_list' => [$this->storedFile('doc_price_list', 'preisliste-2026.pdf', (string) file_get_contents(base_path('tests/Fixtures/pdf/xref-stream-landscape.pdf')))],
            'doc_datasheets' => [$this->storedFile('doc_datasheets', 'pieces.xlsx', 'PK fake spreadsheet')],
            'doc_photos' => [$this->storedFile('doc_photos', 'kt500.jpg', $this->jpeg(1600, 900)), $this->storedFile('doc_photos', 'atelier.jpg', $this->jpeg(800, 800))],
            'doc_brochures' => [$this->storedFile('doc_brochures', 'brochure.pdf', (string) file_get_contents(base_path('tests/Fixtures/pdf/encrypted.pdf')))],
            'doc_certificates' => [$this->storedFile('doc_certificates', 'iso-9001.pdf', (string) file_get_contents(base_path('tests/Fixtures/pdf/xref-stream-portrait.pdf')))],
        ]);
    }

    private function pageCount(string $pdf): int
    {
        return (new Fpdi)->setSourceFile(StreamReader::createByString($pdf));
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
        $this->assertStringContainsString('Fichier introuvable', $html);
        $this->assertStringContainsString('Balayeuses / nettoyeuses de voirie', $html);
        $this->assertStringContainsString('Non-contournement', $html);
        $this->assertStringContainsString('Points clés', $html);
    }

    public function test_the_buyer_version_never_shows_direct_contact_details(): void
    {
        $html = (new SupplierProfileDocument($this->onboardedApplication(), SupplierProfileDocument::BUYER, 'en'))->html();

        $this->assertStringContainsString('Kehrtechnik GmbH', $html);
        $this->assertStringContainsString('Road sweepers / street cleaners', $html);
        $this->assertStringContainsString('1 more document(s) available on request from Sabonea.', $html);
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

    public function test_supplier_pdf_documents_are_appended_as_annexes(): void
    {
        $application = $this->applicationWithDocuments();
        $document = new SupplierProfileDocument($application, SupplierProfileDocument::INTERNAL, 'fr');

        $this->assertSame(['doc_price_list.0', 'doc_brochures.0', 'doc_certificates.0'], $document->documents);

        $html = $document->html();
        $this->assertStringContainsString('Voir annexe A1', $html);
        $this->assertStringContainsString('Voir annexe A2', $html);
        $this->assertStringContainsString('Illisible ou protégé', $html);
        $this->assertStringContainsString('Non inclus', $html);
        $this->assertStringContainsString('kt500.jpg', $html);
        $this->assertStringNotContainsString('<img src', str_replace('<img class="logo"', '', $html), 'Pictures of the supplier are not shown');

        $profilePages = $this->pageCount((new SupplierProfileDocument($application, SupplierProfileDocument::INTERNAL, 'fr', []))->output());
        $pdf = $document->output();

        // Profile, contents of the annexes, the 2 pages of the price list and the certificate.
        $this->assertSame($profilePages + 1 + 2 + 1, $this->pageCount($pdf));
        $this->assertStringContainsString('/Outlines', $pdf);
    }

    public function test_the_buyer_version_includes_no_document_by_default(): void
    {
        $application = $this->applicationWithDocuments();
        $document = new SupplierProfileDocument($application, SupplierProfileDocument::BUYER, 'en');

        $this->assertSame([], $document->documents);

        $html = $document->html();
        $this->assertStringContainsString('6 more document(s) available on request from Sabonea.', $html);
        $this->assertStringNotContainsString('kt500.jpg', $html);
        $this->assertStringNotContainsString('preisliste-2026.pdf', $html);
        $this->assertStringNotContainsString('brochure.pdf', $html);

        // Unknown ids and pictures are ignored: only PDF documents can be appended.
        $chosen = new SupplierProfileDocument($application, SupplierProfileDocument::BUYER, 'en', ['doc_certificates.0', 'doc_photos.0', 'unknown.9']);
        $this->assertSame(['doc_certificates.0'], $chosen->documents);
        $this->assertStringContainsString('See annex A1', $chosen->html());
    }

    public function test_the_documents_can_be_chosen_from_the_url(): void
    {
        $application = $this->applicationWithDocuments();
        $this->actingAs(User::factory()->create());
        $url = fn (string $documents): string => route('supplier-applications.profile-pdf', ['supplierApplication' => $application, 'documents' => $documents]);

        $withoutDocuments = $this->pageCount($this->get($url(''))->assertOk()->getContent());
        $withCertificate = $this->pageCount($this->get($url('doc_certificates.0'))->assertOk()->getContent());

        $this->assertSame($withoutDocuments + 2, $withCertificate);
    }

    public function test_the_generated_pdf_is_kept_a_few_minutes(): void
    {
        $application = $this->onboardedApplication();
        $document = new SupplierProfileDocument($application, SupplierProfileDocument::BUYER, 'de');
        $pdf = $document->output();

        $cached = Storage::disk('local')->files($application->documentsDirectory().'/pdf-cache');
        $this->assertCount(1, $cached);
        $this->assertSame($pdf, Storage::disk('local')->get($cached[0]));
        $this->assertSame($pdf, (new SupplierProfileDocument($application, SupplierProfileDocument::BUYER, 'de'))->output());
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
        $application = $this->applicationWithDocuments();
        $url = fn (string $audience, string $locale, string $documents): string => route('supplier-applications.profile-pdf', [
            'supplierApplication' => $application, 'audience' => $audience, 'locale' => $locale, 'documents' => $documents,
        ]);

        Livewire::test(ViewSupplierApplication::class, ['record' => $application->getRouteKey()])
            ->mountAction('profilePdf')
            ->assertMountedActionModalSee($url('internal', 'fr', 'doc_price_list.0,doc_brochures.0,doc_certificates.0'))
            ->assertMountedActionModalSee('PDF du fournisseur à ajouter en annexe')
            ->assertMountedActionModalDontSee('kt500.jpg')
            ->setActionData(['audience' => SupplierProfileDocument::BUYER, 'locale' => 'de'])
            ->assertMountedActionModalSee($url('buyer', 'de', ''))
            ->setActionData(['documents' => ['doc_certificates.0']])
            ->assertMountedActionModalSee($url('buyer', 'de', 'doc_certificates.0'))
            ->callMountedAction()
            ->assertFileDownloaded('fiche-fournisseur-kehrtechnik-gmbh-acheteur-de.pdf');
    }
}
