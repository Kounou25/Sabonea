<?php

namespace App\Filament\Resources\SupplierApplications\Pages;

use App\Enums\SupplierApplicationStatus;
use App\Filament\Resources\SupplierApplications\Actions\ProfilePdfAction;
use App\Filament\Resources\SupplierApplications\SupplierApplicationResource;
use App\Models\SupplierApplication;
use App\Support\Locales;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

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
                ->modalDescription('Un lien privé vers le formulaire 2 (dossier d\'intégration), valable '.SupplierApplication::ONBOARDING_LINK_DAYS.' jours, sera créé. Il sera pré-rempli avec les réponses du formulaire 1. Vous l\'enverrez vous-même au fournisseur.')
                ->action(function (): void {
                    $this->record->approve();
                    Notification::make()->title('Candidature validée : le lien du dossier est prêt')->body('Cliquez sur « Lien du dossier » pour le copier.')->success()->send();
                }),

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
                    ->action(function (): void {
                        $this->record->approve();
                        Notification::make()->title('Nouveau lien créé')->success()->send();
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
}
