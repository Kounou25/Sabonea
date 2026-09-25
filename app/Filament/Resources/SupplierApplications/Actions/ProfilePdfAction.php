<?php

namespace App\Filament\Resources\SupplierApplications\Actions;

use App\Models\SupplierApplication;
use App\SupplierForms\SupplierProfileDocument;
use App\Support\Locales;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * "Fiche PDF": choose the version, the language and the supplier documents to include, preview the actual PDF, then download it.
 */
class ProfilePdfAction
{
    public static function make(): Action
    {
        return Action::make('profilePdf')
            ->label('Fiche PDF')
            ->icon(Heroicon::OutlinedDocumentText)
            ->color('gray')
            ->modalHeading(fn (SupplierApplication $record): string => 'Fiche PDF : '.$record->company_name)
            ->modalDescription('Aperçu du document tel qu\'il sera téléchargé.')
            ->modalWidth(Width::SevenExtraLarge)
            ->fillForm(fn (SupplierApplication $record): array => [
                'audience' => SupplierProfileDocument::INTERNAL,
                'locale' => Locales::reference(),
                'documents' => SupplierProfileDocument::defaultDocuments($record, SupplierProfileDocument::INTERNAL),
            ])
            ->schema(fn (SupplierApplication $record): array => [
                // Options on the left, preview on the right (stacked on small screens).
                Grid::make(['default' => 1, 'lg' => 3])->schema([
                    Group::make([
                        Select::make('audience')
                            ->label('Version')
                            ->options(SupplierProfileDocument::audiences())
                            ->helperText(fn (Get $get): string => $get('audience') === SupplierProfileDocument::BUYER
                                ? 'À envoyer à un acheteur : aucune coordonnée du fournisseur, la mise en relation passe par Sabonea.'
                                : 'Usage interne : coordonnées, suivi, engagements et notes internes inclus.')
                            ->selectablePlaceholder(false)
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (?string $state, Set $set) => $set('documents', SupplierProfileDocument::defaultDocuments($record, $state ?? SupplierProfileDocument::INTERNAL))),
                        Select::make('locale')
                            ->label('Langue du document')
                            ->options(Locales::all())
                            ->selectablePlaceholder(false)
                            ->required()
                            ->live(),
                        CheckboxList::make('documents')
                            ->label('PDF du fournisseur à ajouter en annexe')
                            ->helperText(fn (Get $get): string => $get('audience') === SupplierProfileDocument::BUYER
                                ? 'Avant d\'envoyer un PDF à un acheteur, vérifiez qu\'il ne contient pas les coordonnées du fournisseur (brochures, tarifs…).'
                                : 'Chaque PDF coché est reproduit page par page à la suite de la fiche.')
                            ->options(SupplierProfileDocument::documentOptions($record))
                            ->descriptions(SupplierProfileDocument::documentDescriptions($record))
                            ->bulkToggleable()
                            ->live()
                            ->visible(SupplierProfileDocument::documentOptions($record) !== []),
                    ])->columnSpan(['lg' => 1]),
                    View::make('filament.supplier-profile-preview')
                        ->viewData(fn (Get $get): array => [
                            'url' => route('supplier-applications.profile-pdf', [
                                'supplierApplication' => $record,
                                'audience' => $get('audience') ?? SupplierProfileDocument::INTERNAL,
                                'locale' => $get('locale') ?? Locales::reference(),
                                'documents' => implode(',', (array) $get('documents')),
                            ]),
                        ])
                        ->columnSpan(['lg' => 2]),
                ]),
            ])
            ->modalSubmitActionLabel('Télécharger le PDF')
            ->modalCancelActionLabel('Fermer')
            ->action(function (array $data, SupplierApplication $record): StreamedResponse {
                $document = new SupplierProfileDocument($record, $data['audience'], $data['locale'], array_values((array) ($data['documents'] ?? [])));
                $content = $document->output();

                return response()->streamDownload(fn () => print ($content), $document->filename(), ['Content-Type' => 'application/pdf']);
            });
    }
}
