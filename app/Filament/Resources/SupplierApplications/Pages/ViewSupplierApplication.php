<?php

namespace App\Filament\Resources\SupplierApplications\Pages;

use App\Enums\SupplierApplicationStatus;
use App\Filament\Resources\SupplierApplications\Actions\ProfilePdfAction;
use App\Filament\Resources\SupplierApplications\SupplierApplicationResource;
use App\Models\SupplierApplication;
use App\Support\Locales;
use App\Support\SupplierMailer;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Throwable;

/**
 * @property SupplierApplication $record
 */
class ViewSupplierApplication extends ViewRecord
{
    protected static string $resource = SupplierApplicationResource::class;

    public function getTitle(): string
    {
        return $this->record->company_name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Valider et créer le lien du dossier')
                ->icon(Heroicon::OutlinedCheckBadge)
                ->color('success')
                ->visible(fn (): bool => in_array($this->record->status, [SupplierApplicationStatus::New, SupplierApplicationStatus::Rejected], true))
                ->requiresConfirmation()
                ->modalDescription('Un lien privé vers le formulaire 2 (dossier d\'intégration), valable '.SupplierApplication::ONBOARDING_LINK_DAYS.' jours, sera créé. Il sera pré-rempli avec les réponses du formulaire 1.')
                ->schema(fn (): array => [$this->sendLinkToggle()])
                ->action(function (array $data): void {
                    $this->record->approve();
                    $this->afterLinkCreated('Candidature validée : le lien du dossier est prêt', (bool) ($data['send_link'] ?? false));
                }),

            Action::make('sendOnboardingLink')
                ->label('Envoyer le lien par e-mail')
                ->icon(Heroicon::OutlinedEnvelope)
                ->color('primary')
                ->visible(fn (): bool => $this->record->hasValidOnboardingLink() && ! $this->record->isOnboardingSubmitted())
                ->modalHeading('Envoyer le lien du dossier au fournisseur')
                ->modalDescription('Le fournisseur reçoit le lien privé vers son dossier d\'intégration, avec sa date de validité.')
                ->fillForm(fn (): array => ['email' => SupplierMailer::supplierEmail($this->record), 'locale' => $this->record->preferredLocale()])
                ->schema(fn (): array => [
                    TextInput::make('email')->label('Destinataire')->email()->required(),
                    Select::make('locale')
                        ->label('Langue de l\'e-mail et du formulaire')
                        ->options(Locales::all())
                        ->helperText('Par défaut, la langue choisie par le fournisseur.')
                        ->selectablePlaceholder(false)
                        ->required(),
                ])
                ->modalSubmitActionLabel('Envoyer')
                ->action(fn (array $data) => $this->sendLink($data['email'], $data['locale'])),

            Action::make('onboardingLink')
                ->label('Lien du dossier')
                ->icon(Heroicon::OutlinedLink)
                ->color('primary')
                ->visible(fn (): bool => $this->record->hasValidOnboardingLink())
                ->modalHeading('Lien privé vers le formulaire 2')
                ->modalDescription(fn (): string => 'Copiez le lien dans la langue du fournisseur et envoyez-le-lui. Valable jusqu\'au '.$this->record->onboarding_token_expires_at->format('d/m/Y').'.')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Fermer')
                ->schema(fn (): array => Locales::active()->map(fn ($language): TextEntry => TextEntry::make("link_{$language->code}")
                    ->label($language->name.($language->code === $this->record->preferredLocale() ? ' (langue choisie par le fournisseur)' : ''))
                    ->state($this->record->onboardingUrl($language->code))
                    ->copyable()
                    ->copyMessage('Lien copié'))
                    ->all()),

            ProfilePdfAction::make(),

            ActionGroup::make([
                Action::make('renewLink')
                    ->label('Créer un nouveau lien')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->visible(fn (): bool => $this->record->status === SupplierApplicationStatus::Approved)
                    ->requiresConfirmation()
                    ->modalDescription('L\'ancien lien ne fonctionnera plus. Les réponses déjà saisies par le fournisseur sont conservées.')
                    ->schema(fn (): array => [$this->sendLinkToggle()])
                    ->action(function (array $data): void {
                        $this->record->approve();
                        $this->afterLinkCreated('Nouveau lien créé', (bool) ($data['send_link'] ?? false));
                    }),
                Action::make('revokeLink')
                    ->label('Désactiver le lien')
                    ->icon(Heroicon::OutlinedNoSymbol)
                    ->color('danger')
                    ->visible(fn (): bool => filled($this->record->onboarding_token))
                    ->requiresConfirmation()
                    ->action(function (): void {
                        $this->record->revokeOnboardingLink();
                        Notification::make()->title('Lien désactivé')->success()->send();
                    }),
                Action::make('reopen')
                    ->label('Rouvrir le dossier au fournisseur')
                    ->icon(Heroicon::OutlinedLockOpen)
                    ->visible(fn (): bool => $this->record->status === SupplierApplicationStatus::OnboardingSubmitted)
                    ->requiresConfirmation()
                    ->modalDescription('Le fournisseur pourra de nouveau modifier son dossier avec son lien (un nouveau lien est créé si le sien a expiré).')
                    ->action(function (): void {
                        $this->record->update(['onboarding_submitted_at' => null]);
                        $this->record->approve();
                        Notification::make()->title('Dossier rouvert')->success()->send();
                    }),
                Action::make('integrate')
                    ->label('Marquer comme intégré')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->visible(fn (): bool => $this->record->status === SupplierApplicationStatus::OnboardingSubmitted)
                    ->requiresConfirmation()
                    ->action(function (): void {
                        $this->record->update(['status' => SupplierApplicationStatus::Integrated]);
                        $this->record->revokeOnboardingLink();
                        Notification::make()->title('Fournisseur marqué comme intégré')->success()->send();
                    }),
                Action::make('reject')
                    ->label('Refuser')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->visible(fn (): bool => ! in_array($this->record->status, [SupplierApplicationStatus::Rejected, SupplierApplicationStatus::Integrated], true))
                    ->requiresConfirmation()
                    ->action(function (): void {
                        $this->record->update(['status' => SupplierApplicationStatus::Rejected]);
                        $this->record->revokeOnboardingLink();
                        Notification::make()->title('Candidature refusée')->success()->send();
                    }),
                EditAction::make()->label('Statut et notes'),
                DeleteAction::make(),
            ])->label('Autres actions')->button()->color('gray'),
        ];
    }

    private function sendLinkToggle(): Toggle
    {
        return Toggle::make('send_link')
            ->label('Envoyer le lien par e-mail au fournisseur')
            ->helperText('À '.SupplierMailer::supplierEmail($this->record).', en '.(Locales::all()[$this->record->preferredLocale()] ?? $this->record->preferredLocale()).' (langue choisie par le fournisseur). Sinon, copiez le lien depuis « Lien du dossier ».')
            ->default(true);
    }

    private function afterLinkCreated(string $title, bool $sendLink): void
    {
        if ($sendLink) {
            $this->sendLink(SupplierMailer::supplierEmail($this->record), $this->record->preferredLocale(), $title);

            return;
        }

        Notification::make()->title($title)->body('Cliquez sur « Lien du dossier » pour le copier, ou sur « Envoyer le lien par e-mail ».')->success()->send();
    }

    private function sendLink(string $email, string $locale, ?string $title = null): void
    {
        try {
            SupplierMailer::sendOnboardingLink($this->record, $email, $locale);
        } catch (Throwable $exception) {
            report($exception);
            Notification::make()
                ->title(($title ? "{$title}, mais l'e-mail" : 'L\'e-mail').' n\'a pas pu être envoyé')
                ->body('Vérifiez l\'adresse et la configuration e-mail, ou copiez le lien depuis « Lien du dossier ».')
                ->danger()
                ->persistent()
                ->send();

            return;
        }

        Notification::make()->title($title ?? 'Lien envoyé')->body("Le lien du dossier a été envoyé à {$email}.")->success()->send();
    }
}
