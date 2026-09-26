<?php

namespace Tests\Feature;

use App\Enums\ContactMessageStatus;
use App\Enums\NeedRequestStatus;
use App\Filament\Resources\ContactMessages\Pages\ListContactMessages;
use App\Filament\Resources\ContactMessages\Pages\ViewContactMessage;
use App\Models\ContactMessage;
use App\Models\EquipmentType;
use App\Models\FormOption;
use App\Models\NeedRequest;
use App\Models\Sector;
use App\Models\User;
use App\Notifications\ContactMessageReceivedNotification;
use App\Notifications\NewContactMessageNotification;
use App\Notifications\NewNeedRequestNotification;
use App\Support\Honeypot;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class FormSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
        Notification::fake();
    }

    /**
     * @return array<string, mixed>
     */
    private function validNeedRequest(): array
    {
        return [
            'name' => 'Jane Buyer',
            'company' => 'Airport Co',
            'email' => 'jane@example.com',
            'sector_id' => Sector::query()->value('id'),
            'equipment_type_id' => EquipmentType::query()->value('id'),
            'country' => 'FR',
            'deadline_option_id' => FormOption::query()->where('field', FormOption::NEED_DEADLINE)->value('id'),
            'message' => '3 sweepers',
        ];
    }

    public function test_need_request_is_stored_and_the_team_is_notified(): void
    {
        $sector = Sector::query()->first();
        $equipmentType = EquipmentType::query()->first();
        $deadline = FormOption::query()->where('field', FormOption::NEED_DEADLINE)->first();

        $response = $this->post('/en/expression-de-besoin', [
            'name' => 'Jane Buyer',
            'company' => 'Airport Co',
            'email' => 'jane@example.com',
            'phone' => '+33 1 23 45 67 89',
            'sector_id' => $sector->id,
            'equipment_type_id' => $equipmentType->id,
            'country' => 'FR',
            'deadline_option_id' => $deadline->id,
            'message' => '3 sweepers',
        ]);

        $response->assertRedirect('/en/expression-de-besoin')->assertSessionHas('need_sent');

        $needRequest = NeedRequest::query()->sole();
        $this->assertSame('en', $needRequest->locale);
        $this->assertSame(NeedRequestStatus::New, $needRequest->status);
        $this->assertTrue($needRequest->sector->is($sector));

        Notification::assertSentTo(
            new AnonymousNotifiable,
            NewNeedRequestNotification::class,
            fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === 'contact@sabonea.com',
        );

        $this->followRedirects($response)->assertSee('Thank you, your request has been sent.');
    }

    public function test_buyer_form_uses_the_shared_reference_lists(): void
    {
        $this->get('/fr/expression-de-besoin')
            ->assertSee('Hôpitaux et établissements de santé')
            ->assertSee('Pièces détachées et consommables')
            ->assertSee('Allemagne');

        $otherSector = Sector::query()->where('key', 'SEC_OTHER')->sole();

        $this->post('/fr/expression-de-besoin', [
            'name' => 'Jean Acheteur',
            'email' => 'jean@example.com',
            'sector_id' => $otherSector->id,
            'country' => 'BE',
        ])->assertSessionHasNoErrors();

        $needRequest = NeedRequest::query()->sole();
        $this->assertTrue($needRequest->sector->is($otherSector));
        $this->assertSame('BE', $needRequest->country);
    }

    public function test_need_request_requires_name_and_valid_email(): void
    {
        $this->from('/fr/expression-de-besoin')
            ->post('/fr/expression-de-besoin', ['email' => 'not-an-email'])
            ->assertRedirect('/fr/expression-de-besoin')
            ->assertSessionHasErrors([
                'name' => 'Ce champ est obligatoire.',
                'email' => 'Veuillez saisir une adresse e-mail valide.',
            ]);

        $this->assertDatabaseCount('need_requests', 0);
        Notification::assertNothingSent();
    }

    public function test_contact_message_is_stored_and_the_team_is_notified(): void
    {
        $subject = FormOption::query()->where('field', FormOption::CONTACT_SUBJECT)->first();

        $this->post('/de/contact', [
            'name' => 'Hans Lieferant',
            'email' => 'hans@example.com',
            'subject_option_id' => $subject->id,
            'message' => 'Guten Tag',
        ])->assertRedirect('/de/contact#contact-form')->assertSessionHas('contact_sent');

        $message = ContactMessage::query()->sole();
        $this->assertSame('de', $message->locale);
        $this->assertSame(ContactMessageStatus::New, $message->status);

        // The team gets the full message and replies straight to the sender.
        Notification::assertSentTo(
            new AnonymousNotifiable,
            NewContactMessageNotification::class,
            function (NewContactMessageNotification $notification, array $channels, AnonymousNotifiable $notifiable): bool {
                $mail = $notification->toMail($notifiable);

                return $notifiable->routes['mail'] === 'contact@sabonea.com'
                    && $notification->locale === 'fr'
                    && $mail->replyTo === [['hans@example.com', 'Hans Lieferant']]
                    && str_contains(implode(' ', array_map('strval', $mail->introLines)), 'Guten Tag');
            },
        );

        // The sender gets an acknowledgement in the language of the page.
        Notification::assertSentTo(
            new AnonymousNotifiable,
            ContactMessageReceivedNotification::class,
            fn (ContactMessageReceivedNotification $notification, array $channels, AnonymousNotifiable $notifiable): bool => $notifiable->routes['mail'] === 'hans@example.com' && $notification->locale === 'de',
        );
    }

    public function test_the_acknowledgement_is_written_in_the_language_of_the_page(): void
    {
        $message = ContactMessage::create([
            'name' => 'Li Wei',
            'email' => 'li@example.com',
            'message' => '你好',
            'locale' => 'zh',
        ]);

        app()->setLocale('zh');
        $mail = (new ContactMessageReceivedNotification($message))->toMail(new AnonymousNotifiable);
        $this->assertSame('我们已收到您的留言', $mail->subject);
        $this->assertSame('Li Wei，您好：', $mail->greeting);
    }

    public function test_the_contact_form_still_works_when_the_mail_server_is_down(): void
    {
        Notification::swap(app(ChannelManager::class));
        config(['mail.default' => 'smtp', 'mail.mailers.smtp.host' => '127.0.0.1', 'mail.mailers.smtp.port' => 1]);

        $this->post('/fr/contact', [
            'name' => 'Marie',
            'email' => 'marie@example.com',
            'message' => 'Bonjour',
        ])->assertRedirect('/fr/contact#contact-form')->assertSessionHas('contact_sent');

        $this->assertDatabaseCount('contact_messages', 1);
    }

    public function test_contact_subject_must_belong_to_the_contact_list(): void
    {
        $deadline = FormOption::query()->where('field', FormOption::NEED_DEADLINE)->first();

        $this->post('/fr/contact', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'subject_option_id' => $deadline->id,
            'message' => 'Bonjour',
        ])->assertSessionHasErrors('subject_option_id');
    }

    public function test_messages_caught_by_the_anti_spam_trap_are_kept_apart_without_notification(): void
    {
        $this->post('/fr/contact', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Spam',
            Honeypot::FIELD => 'http://spam.example',
        ])->assertRedirect('/fr/contact#contact-form')->assertSessionHas('contact_sent');

        $this->assertSame(ContactMessageStatus::Spam, ContactMessage::query()->sole()->status);
        Notification::assertNothingSent();

        $this->post('/fr/expression-de-besoin', [...$this->validNeedRequest(), Honeypot::FIELD => 'x'])->assertRedirect('/fr/expression-de-besoin');

        $this->assertSame(NeedRequestStatus::Spam, NeedRequest::query()->sole()->status);
        Notification::assertNothingSent();
    }

    public function test_a_field_named_website_filled_by_the_browser_no_longer_hides_a_message(): void
    {
        $this->post('/fr/contact', [
            'name' => 'Anna Schmidt',
            'email' => 'anna@example.com',
            'message' => 'Bonjour',
            'website' => 'https://www.example.com',
        ])->assertRedirect('/fr/contact#contact-form');

        $this->assertSame(ContactMessageStatus::New, ContactMessage::query()->sole()->status);
    }

    public function test_spam_is_listed_apart_in_the_back_office(): void
    {
        Filament::setCurrentPanel('admin');
        $this->actingAs(User::factory()->create());
        $message = ContactMessage::create(['name' => 'Anna', 'email' => 'anna@example.com', 'message' => 'Bonjour', 'locale' => 'fr']);
        $spam = ContactMessage::create(['name' => 'Bot', 'email' => 'bot@example.com', 'message' => 'Spam', 'locale' => 'fr', 'status' => ContactMessageStatus::Spam]);

        Livewire::test(ListContactMessages::class)
            ->assertCanSeeTableRecords([$message])
            ->assertCanNotSeeTableRecords([$spam])
            ->set('activeTab', 'spam')
            ->assertCanSeeTableRecords([$spam])
            ->assertCanNotSeeTableRecords([$message]);

        Livewire::test(ViewContactMessage::class, ['record' => $spam->getRouteKey()])
            ->callAction('notSpam');

        $this->assertSame(ContactMessageStatus::Read, $spam->refresh()->status);
    }
}
