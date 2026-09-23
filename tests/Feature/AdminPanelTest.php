<?php

namespace Tests\Feature;

use App\Enums\ContactMessageStatus;
use App\Filament\Resources\ContactMessages\Pages\ViewContactMessage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\RelationManagers\SectionsRelationManager;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
        Filament::setCurrentPanel('admin');
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk();
    }

    public function test_deactivated_users_cannot_access_the_back_office(): void
    {
        $this->actingAs(User::factory()->inactive()->create())
            ->get('/admin')
            ->assertForbidden();
    }

    /**
     * @return array<string, array{string}>
     */
    public static function editorUrls(): array
    {
        return [
            'dashboard' => ['/admin'],
            'need requests' => ['/admin/need-requests'],
            'contact messages' => ['/admin/contact-messages'],
            'pages' => ['/admin/pages'],
            'hero slides' => ['/admin/hero-slides'],
            'hero slide creation' => ['/admin/hero-slides/create'],
            'sectors' => ['/admin/sectors'],
            'equipment types' => ['/admin/equipment-types'],
            'form options' => ['/admin/form-options'],
            'ui translations' => ['/admin/ui-translations'],
        ];
    }

    #[DataProvider('editorUrls')]
    public function test_editors_can_manage_content_and_requests(string $url): void
    {
        $this->actingAs(User::factory()->create())->get($url)->assertOk();
    }

    /**
     * @return array<string, array{string}>
     */
    public static function adminUrls(): array
    {
        return [
            'settings' => ['/admin/reglages'],
            'languages' => ['/admin/languages'],
            'users' => ['/admin/users'],
        ];
    }

    #[DataProvider('adminUrls')]
    public function test_only_admins_can_manage_administration(string $url): void
    {
        $this->actingAs(User::factory()->create())->get($url)->assertForbidden();
        $this->actingAs(User::factory()->admin()->create())->get($url)->assertOk();
    }

    public function test_every_page_can_be_edited(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (Page::all() as $page) {
            $this->get("/admin/pages/{$page->id}/edit")->assertOk();
        }
    }

    public function test_a_section_can_be_edited_in_every_language(): void
    {
        $this->actingAs(User::factory()->create());

        $page = Page::query()->where('key', 'a-propos')->first();
        $section = $page->sections()->where('key', 'story')->first();

        Livewire::test(SectionsRelationManager::class, ['ownerRecord' => $page, 'pageClass' => EditPage::class])
            ->callAction(TestAction::make('edit')->table($section), data: [
                'title' => [
                    'fr' => 'Notre belle histoire',
                    'en' => 'Our great story',
                    'de' => '',
                    'zh' => '',
                ],
            ])
            ->assertHasNoFormErrors();

        $this->assertSame('Notre belle histoire', $section->refresh()->t('title', 'fr'));

        $this->get('/fr/a-propos')->assertSee('Notre belle histoire');
        $this->get('/en/a-propos')->assertSee('Our great story');
        $this->get('/de/a-propos')->assertSee('Notre belle histoire');
    }

    public function test_the_reference_language_is_required(): void
    {
        $this->actingAs(User::factory()->create());

        $page = Page::query()->where('key', 'a-propos')->first();
        $section = $page->sections()->where('key', 'story')->first();

        Livewire::test(SectionsRelationManager::class, ['ownerRecord' => $page, 'pageClass' => EditPage::class])
            ->callAction(TestAction::make('edit')->table($section), data: [
                'title' => ['fr' => '', 'en' => 'Our story', 'de' => '', 'zh' => ''],
            ])
            ->assertHasFormErrors(['title.fr' => 'required']);
    }

    public function test_opening_a_contact_message_marks_it_as_read(): void
    {
        $this->actingAs(User::factory()->create());

        $message = ContactMessage::create([
            'name' => 'Jean',
            'email' => 'jean@example.com',
            'message' => 'Bonjour',
            'locale' => 'fr',
        ]);

        Livewire::test(ViewContactMessage::class, ['record' => $message->getRouteKey()])->assertOk();

        $this->assertSame(ContactMessageStatus::Read, $message->refresh()->status);
    }
}
