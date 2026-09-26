<?php

namespace App\Filament\Resources\SupplierApplications\Pages;

use App\Enums\SupplierApplicationStatus;
use App\Filament\Resources\SupplierApplications\SupplierApplicationResource;
use App\Models\SupplierApplication;
use App\SupplierForms\SupplierApplicationExporter;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ListSupplierApplications extends ListRecords
{
    protected static string $resource = SupplierApplicationResource::class;

    public function getSubheading(): string
    {
        return 'Formulaire 1 (prise de contact) puis formulaire 2 (dossier d\'intégration, par lien privé). Les réponses de test sont exclues des exports.';
    }

    /**
     * One tab per step, with the number of applications (test answers left out, as in the list by default).
     */
    public function getTabs(): array
    {
        $counts = SupplierApplication::query()
            ->toBase()
            ->where('is_test', false)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $count = fn (SupplierApplicationStatus ...$statuses): ?int => array_sum(array_map(fn (SupplierApplicationStatus $status): int => (int) ($counts[$status->value] ?? 0), $statuses)) ?: null;

        $tab = fn (string $label, SupplierApplicationStatus ...$statuses): Tab => Tab::make($label)
            ->badge($count(...$statuses))
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereIn('status', $statuses));

        return [
            'all' => Tab::make('Toutes'),
            'to-process' => $tab('À traiter', ...SupplierApplicationStatus::toProcess())->icon(Heroicon::OutlinedBellAlert)->badgeColor('warning'),
            'new' => $tab('Nouveaux contacts', SupplierApplicationStatus::New),
            'approved' => $tab('Dossier à remplir', SupplierApplicationStatus::Approved),
            'received' => $tab('Dossiers reçus', SupplierApplicationStatus::OnboardingSubmitted),
            'integrated' => $tab('Intégrés', SupplierApplicationStatus::Integrated),
            'rejected' => $tab('Refusés', SupplierApplicationStatus::Rejected),
        ];
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
