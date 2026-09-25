<?php

namespace Tests\Feature;

use App\Filament\Resources\SupplierApplications\Pages\ViewSupplierApplication;
use App\Livewire\SupplierContactForm;
use App\Livewire\SupplierOnboardingForm;
use App\Models\Setting;
use App\Models\SupplierApplication;
use App\Models\User;
use App\Notifications\NewSupplierSubmissionNotification;
use App\Notifications\SupplierOnboardingLinkNotification;
use App\Notifications\SupplierSubmissionReceivedNotification;
use App\Support\Mailing;
use App\Support\SupplierForms;
use App\Support\SupplierMailer;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Sleep;
use Livewire\Livewire;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mime\Part\DataPart;
use Tests\Concerns\BuildsSupplierAnswers;
use Tests\TestCase;

class SupplierEmailsTest extends TestCase
{
    use BuildsSupplierAnswers;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');
        $this->seed();
        Setting::put(SupplierForms::PUBLIC_SETTING, '1');
    }

    private function submitContactForm(string $locale = 'de'): SupplierApplication
    {
        app()->setLocale($locale);
        $component = Livewire::test(SupplierContactForm::class);

        foreach ($this->validContactAnswers() as $key => $value) {
            $component->set("answers.{$key}", $value);
        }

        $component->call('submit')->assertHasNoErrors()->assertSet('submitted', true);
        app()->setLocale('fr');

        return SupplierApplication::query()->latest('id')->firstOrFail();
    }

    /**
     * Text of the rendered e-mail (HTML version).
     */
    private function render(object $notification, string $locale): string
    {
        return html_entity_decode((string) $this->mail($notification, $locale)->render(), ENT_QUOTES);
    }

    /**
     * The e-mail built in the given language (as the notification system does with ->locale()).
     */
    private function mail(object $notification, string $locale): MailMessage
    {
        $previous = app()->getLocale();
        app()->setLocale($locale);

        try {
            return $notification->toMail(new AnonymousNotifiable);
        } finally {
            app()->setLocale($previous);
        }
    }

    public function test_form_1_sends_an_acknowledgement_to_the_supplier_and_a_notification_to_the_team(): void
    {
        Notification::fake();

        $application = $this->submitContactForm('de');

        Notification::assertSentOnDemand(NewSupplierSubmissionNotification::class, function (NewSupplierSubmissionNotification $notification, array $channels, AnonymousNotifiable $notifiable) use ($application): bool {
            $html = $this->render($notification, 'fr');

            return $notifiable->routes['mail'] === 'contact@sabonea.com'
                && $notification->locale === 'fr'
                && str_contains($html, 'Kehrtechnik GmbH')
                && str_contains($html, 'Balayeuses / nettoyeuses de voirie')
                && str_contains($html, "/admin/supplier-applications/{$application->id}");
        });

        Notification::assertSentOnDemand(SupplierSubmissionReceivedNotification::class, function (SupplierSubmissionReceivedNotification $notification, array $channels, AnonymousNotifiable $notifiable): bool {
            $html = $this->render($notification, $notification->locale);

            // Same end message as on the page, in the language of the form.
            return $notifiable->routes['mail'] === 'hans@kehrtechnik.example'
                && $notification->locale === 'de'
                && $this->mail($notification, 'de')->subject === 'Wir haben Ihre Bewerbung erhalten'
                && str_contains($html, 'Unser Team meldet sich innerhalb von 48 Stunden')
                && str_contains($html, 'Prüfung Ihres Profils');
        });
    }

    public function test_form_2_sends_an_acknowledgement_to_the_supplier_and_a_notification_to_the_team(): void
    {
        $application = $this->approvedApplication();
        Notification::fake();

        $component = Livewire::test(SupplierOnboardingForm::class, ['token' => $application->onboarding_token]);

        foreach ([...$this->validOnboardingOnlyAnswers(), 'contact_email' => 'export@kehrtechnik.example'] as $key => $value) {
            $component->set("answers.{$key}", $value);
        }

        $component->set('step', 9)->call('submit')->assertHasNoErrors()->assertSet('submitted', true);

        Notification::assertSentOnDemand(NewSupplierSubmissionNotification::class, function (NewSupplierSubmissionNotification $notification) use ($application): bool {
            $html = $this->render($notification, 'fr');

            return str_contains($html, "Dossier d'intégration reçu")
                && str_contains($html, 'Fiche PDF')
                && str_contains($html, "/admin/supplier-applications/{$application->id}");
        });

        // The e-mail corrected in form 2 is used.
        Notification::assertSentOnDemand(SupplierSubmissionReceivedNotification::class, function (SupplierSubmissionReceivedNotification $notification, array $channels, AnonymousNotifiable $notifiable): bool {
            return $notifiable->routes['mail'] === 'export@kehrtechnik.example'
                && str_contains($this->render($notification, 'fr'), 'étudiera votre dossier');
        });
    }

    public function test_the_onboarding_link_can_be_emailed_when_approving_or_later(): void
    {
        $this->actingAs(User::factory()->create());
        Filament::setCurrentPanel('admin');
        $application = $this->submitContactForm('de');
        Notification::fake();

        Livewire::test(ViewSupplierApplication::class, ['record' => $application->getRouteKey()])
            ->callAction('approve', ['send_link' => true])
            ->assertHasNoActionErrors();

        $application->refresh();

        Notification::assertSentOnDemand(SupplierOnboardingLinkNotification::class, function (SupplierOnboardingLinkNotification $notification, array $channels, AnonymousNotifiable $notifiable) use ($application): bool {
            $html = $this->render($notification, 'de');

            return $notifiable->routes['mail'] === 'hans@kehrtechnik.example'
                && $notification->locale === 'de'
                && str_contains($html, 'Unterlagen vervollständigen')
                && str_contains($html, (string) $application->onboardingUrl('de'));
        });

        // Sent again later, to another address and in English.
        Notification::fake();

        Livewire::test(ViewSupplierApplication::class, ['record' => $application->getRouteKey()])
            ->callAction('sendOnboardingLink', ['email' => 'sales@kehrtechnik.example', 'locale' => 'en'])
            ->assertHasNoActionErrors();

        Notification::assertSentOnDemand(SupplierOnboardingLinkNotification::class, function (SupplierOnboardingLinkNotification $notification, array $channels, AnonymousNotifiable $notifiable) use ($application): bool {
            return $notifiable->routes['mail'] === 'sales@kehrtechnik.example'
                && str_contains($this->render($notification, 'en'), (string) $application->onboardingUrl('en'));
        });
    }

    public function test_approving_without_sending_does_not_email_the_supplier(): void
    {
        $this->actingAs(User::factory()->create());
        Filament::setCurrentPanel('admin');
        $application = $this->submitContactForm();
        Notification::fake();

        Livewire::test(ViewSupplierApplication::class, ['record' => $application->getRouteKey()])
            ->callAction('approve', ['send_link' => false])
            ->assertHasNoActionErrors();

        Notification::assertNothingSent();
    }

    public function test_emails_show_the_sabonea_logo_embedded_in_the_message(): void
    {
        SupplierMailer::sendOnboardingLink($this->approvedApplication(), 'someone@example.com', 'fr');

        $sent = app('mailer')->getSymfonyTransport()->messages()->last();
        $email = $sent->getOriginalMessage();

        $this->assertStringContainsString('cid:sabonea-logo', (string) $email->getHtmlBody());
        $this->assertTrue(collect($email->getAttachments())->contains(fn (DataPart $part): bool => $part->getFilename() === 'sabonea-logo'));
        $this->assertStringContainsString('Content-Disposition: inline', $sent->toString());
    }

    public function test_a_mail_server_failure_does_not_lose_the_submission(): void
    {
        Sleep::fake();
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => '127.0.0.1',
            'mail.mailers.smtp.port' => 1,
            'mail.mailers.smtp.timeout' => 2,
        ]);

        $this->submitContactForm();

        $this->assertSame(1, SupplierApplication::query()->count());

        // Each of the 2 e-mails was tried 3 times, with a pause before each new attempt.
        Sleep::assertSleptTimes(4);
    }

    public function test_a_temporary_mail_server_failure_is_retried(): void
    {
        Sleep::fake();
        $attempts = 0;

        $sent = Mailing::safely(function () use (&$attempts): void {
            if (++$attempts < 3) {
                throw new TransportException('Connection to "smtp.office365.com:587" timed out.');
            }
        }, retry: true);

        $this->assertTrue($sent);
        $this->assertSame(3, $attempts);
        Sleep::assertSequence([Sleep::for(3)->seconds(), Sleep::for(10)->seconds()]);
    }

    public function test_answers_sent_all_at_once_by_the_browser_are_handled(): void
    {
        $application = $this->approvedApplication();
        $component = Livewire::test(SupplierOnboardingForm::class, ['token' => $application->onboarding_token]);

        // The whole list comes back when an answer appears that did not exist yet ("Other" free text).
        $component->set('answers', [...$component->get('answers'), 'certifications' => ['CERT_CE', 'CERT_NONE'], 'certifications_cert_other' => 'EN 13019'])
            ->assertHasNoErrors()
            ->assertSet('answers.certifications', ['CERT_NONE']);

        $this->assertSame('EN 13019', $application->refresh()->onboarding_answers['certifications_cert_other']);
    }
}
