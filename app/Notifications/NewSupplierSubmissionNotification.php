<?php

namespace App\Notifications;

use App\Filament\Resources\SupplierApplications\SupplierApplicationResource;
use App\Models\SupplierApplication;
use App\SupplierForms\OptionLists;
use App\SupplierForms\SupplierOnboardingForm;
use App\Support\Countries;
use App\Support\Locales;
use App\Support\SupplierMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to the Sabonea team for each supplier form sent (form 1 or form 2), with a link to the file in the back-office.
 * "Reply" writes to the supplier.
 */
class NewSupplierSubmissionNotification extends Notification
{
    use Queueable;

    public function __construct(public SupplierApplication $application, public string $form) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $application = $this->application;
        $answers = $application->contact_answers ?? [];
        $isOnboarding = $this->form === SupplierMailer::ONBOARDING_FORM;
        $test = $application->is_test ? '[TEST] ' : '';
        $locale = $isOnboarding ? $application->onboarding_locale : $application->contact_locale;
        $role = OptionLists::label('contact_role', $answers['contact_role'] ?? null, 'fr');

        $mail = (new MailMessage)
            ->subject($test.($isOnboarding ? 'Dossier d\'intégration reçu : ' : 'Nouvelle candidature fournisseur : ').$application->company_name)
            ->replyTo(SupplierMailer::supplierEmail($application), $application->contact_name)
            ->greeting($isOnboarding ? 'Dossier d\'intégration reçu' : 'Nouvelle candidature fournisseur')
            ->line($isOnboarding
                ? "{$application->company_name} a envoyé son dossier d'intégration complet (formulaire 2)."
                : "{$application->company_name} a rempli le formulaire « Devenir fournisseur ».")
            ->line('**Entreprise :** '.$application->company_name.' ('.(Countries::name($application->country, 'fr') ?? '—').')')
            ->line('**Contact :** '.$application->contact_name.($role ? " — {$role}" : ''))
            ->line('**E-mail :** '.SupplierMailer::supplierEmail($application))
            ->line('**Téléphone :** '.($application->contact_phone ?: '—'))
            ->line('**Profil :** '.(OptionLists::label('supplier_type', $answers['supplier_type'] ?? null, 'fr') ?? '—'))
            ->line('**Équipements :** '.(OptionLists::labels(OptionLists::EQUIPMENT, $answers['equipment_categories'] ?? [], 'fr') ?: '—'))
            ->line('**Langue du formulaire :** '.(Locales::all()[$locale] ?? strtoupper((string) $locale)));

        if ($isOnboarding) {
            $documents = collect(SupplierOnboardingForm::DOCUMENT_KEYS)
                ->sum(fn (string $key): int => count((array) ($application->onboarding_answers[$key] ?? [])));

            $mail->line("**Documents joints :** {$documents}")
                ->action('Voir le dossier dans le back-office', SupplierApplicationResource::getUrl('view', ['record' => $application]))
                ->line('La fiche PDF du fournisseur se génère depuis le dossier (bouton « Fiche PDF »).');
        } else {
            $mail->line('**Produit phare :** '.($answers['flagship_product'] ?? '—'))
                ->action('Voir la candidature dans le back-office', SupplierApplicationResource::getUrl('view', ['record' => $application]))
                ->line('Pour lui envoyer le formulaire 2, validez la candidature depuis le back-office.');
        }

        if ($application->is_test) {
            $mail->line('Réponse de test (envoyée par un utilisateur du back-office) : elle n\'apparaît pas dans les exports.');
        }

        return $mail->salutation('Site Sabonea');
    }
}
