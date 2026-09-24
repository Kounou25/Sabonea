<?php

namespace App\Filament\Resources\SupplierApplications\Pages;

use App\Filament\Resources\SupplierApplications\SupplierApplicationResource;
use App\SupplierForms\SupplierApplicationExporter;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListSupplierApplications extends ListRecords
{
    protected static string $resource = SupplierApplicationResource::class;

    public function getSubheading(): string
    {
        return 'Formulaire 1 (prise de contact) puis formulaire 2 (dossier d\'intégration, par lien privé). Les réponses de test sont exclues des exports.';
    }

    protected function getHeaderActions(): array
    {
        $export = fn (string $form, string $format): Action => Action::make("export_{$form}_{$format}")
            ->label(($form === SupplierApplicationExporter::CONTACT ? 'Formulaire 1 : prises de contact' : 'Formulaire 2 : dossiers reçus').' ('.strtoupper($format).')')
            ->action(fn () => app(SupplierApplicationExporter::class)->download($form, $format));

        return [
            ActionGroup::make([
                $export(SupplierApplicationExporter::CONTACT, 'xlsx'),
                $export(SupplierApplicationExporter::CONTACT, 'csv'),
                $export(SupplierApplicationExporter::ONBOARDING, 'xlsx'),
                $export(SupplierApplicationExporter::ONBOARDING, 'csv'),
            ])
                ->label('Exporter')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->button()
                ->color('gray'),
        ];
    }
}
