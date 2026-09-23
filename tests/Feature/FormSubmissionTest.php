<?php

namespace Tests\Feature;

use App\Enums\ContactMessageStatus;
use App\Enums\NeedRequestStatus;
use App\Models\ContactMessage;
use App\Models\EquipmentType;
use App\Models\FormOption;
use App\Models\NeedRequest;
use App\Models\Sector;
use App\Notifications\NewContactMessageNotification;
use App\Notifications\NewNeedRequestNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
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
            'country' => 'France',
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

    public function test_other_sector_and_equipment_are_stored_as_empty(): void
    {
        $this->post('/fr/expression-de-besoin', [
            'name' => 'Jean Acheteur',
            'email' => 'jean@example.com',
            'sector_id' => 'other',
            'equipment_type_id' => 'other',
        ])->assertSessionHasNoErrors();

        $needRequest = NeedRequest::query()->sole();
        $this->assertNull($needRequest->sector_id);
        $this->assertNull($needRequest->equipment_type_id);
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
        ])->assertRedirect('/de/contact')->assertSessionHas('contact_sent');

        $message = ContactMessage::query()->sole();
        $this->assertSame('de', $message->locale);
        $this->assertSame(ContactMessageStatus::New, $message->status);

        Notification::assertSentTo(new AnonymousNotifiable, NewContactMessageNotification::class);
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

    public function test_honeypot_submissions_are_ignored(): void
    {
        $this->post('/fr/contact', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Spam',
            'website' => 'http://spam.example',
        ])->assertRedirect('/fr/contact');

        $this->assertDatabaseCount('contact_messages', 0);
        Notification::assertNothingSent();
    }
}
