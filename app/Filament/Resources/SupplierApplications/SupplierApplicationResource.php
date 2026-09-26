<?php

namespace App\Filament\Resources\SupplierApplications;

use App\Enums\SupplierApplicationStatus;
use App\Filament\Resources\SupplierApplications\Pages\EditSupplierApplication;
use App\Filament\Resources\SupplierApplications\Pages\ListSupplierApplications;
use App\Filament\Resources\SupplierApplications\Pages\ViewSupplierApplication;
use App\Filament\Resources\SupplierApplications\Schemas\SupplierApplicationForm;
use App\Filament\Resources\SupplierApplications\Schemas\SupplierApplicationInfolist;
use App\Filament\Resources\SupplierApplications\Tables\SupplierApplicationsTable;
use App\Models\SupplierApplication;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SupplierApplicationResource extends Resource
{
    protected static ?string $model = SupplierApplication::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static string|UnitEnum|null $navigationGroup = 'Fournisseurs';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'candidature fournisseur';

    protected static ?string $pluralModelLabel = 'candidatures fournisseurs';

    protected static ?string $recordTitleAttribute = 'company_name';

    public static function getNavigationBadge(): ?string
    {
        // Same count as the "À traiter" tab of the list (test answers left out).
        $count = SupplierApplication::query()
            ->whereIn('status', SupplierApplicationStatus::toProcess())
            ->where('is_test', false)
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): string
    {
        return 'Nouveaux contacts et dossiers reçus à traiter';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return SupplierApplicationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SupplierApplicationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplierApplicationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupplierApplications::route('/'),
            'view' => ViewSupplierApplication::route('/{record}'),
            'edit' => EditSupplierApplication::route('/{record}/edit'),
        ];
    }
}
