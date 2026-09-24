<?php

namespace App\Filament\Resources\SupplierApplications\Actions;

use App\Models\SupplierApplication;
use App\SupplierForms\SupplierProfileDocument;
use App\Support\Locales;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * "Fiche PDF": choose the version and the language, preview the actual PDF, then download it.
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
            ->modalDescription('Aperçu du document tel qu\'il sera téléchargé. La version « acheteur » ne contient aucune coordonnée du fournisseur.')
            ->modalWidth(Width::SevenExtraLarge)
            ->fillForm(['audience' => SupplierProfileDocument::INTERNAL, 'locale' => Locales::reference()])
            ->schema(fn (SupplierApplication $record): array => [
                Grid::make(2)->schema([
                    Select::make('audience')
                        ->label('Version')
                        ->options(SupplierProfileDocument::audiences())
                        ->selectablePlaceholder(false)
                        ->required()
                        ->live(),
                    Select::make('locale')
                        ->label('Langue du document')
                        ->options(Locales::all())
                        ->selectablePlaceholder(false)
                        ->required()
                        ->live(),
                ]),
                View::make('filament.supplier-profile-preview')
                    ->viewData(fn (Get $get): array => [
                        'url' => route('supplier-applications.profile-pdf', [
                            'supplierApplication' => $record,
                            'audience' => $get('audience') ?? SupplierProfileDocument::INTERNAL,
                            'locale' => $get('locale') ?? Locales::reference(),
                        ]),
                    ]),
            ])
            ->modalSubmitActionLabel('Télécharger le PDF')
            ->modalCancelActionLabel('Fermer')
            ->action(function (array $data, SupplierApplication $record): StreamedResponse {
                $document = new SupplierProfileDocument($record, $data['audience'], $data['locale']);
                $content = $document->pdf()->output();

                return response()->streamDownload(fn () => print ($content), $document->filename(), ['Content-Type' => 'application/pdf']);
            });
    }
}
