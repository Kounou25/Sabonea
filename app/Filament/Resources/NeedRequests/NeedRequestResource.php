<?php

namespace App\Filament\Resources\NeedRequests;

use App\Enums\NeedRequestStatus;
use App\Filament\Resources\NeedRequests\Pages\EditNeedRequest;
use App\Filament\Resources\NeedRequests\Pages\ListNeedRequests;
use App\Filament\Resources\NeedRequests\Pages\ViewNeedRequest;
use App\Filament\Resources\NeedRequests\Schemas\NeedRequestForm;
use App\Filament\Resources\NeedRequests\Schemas\NeedRequestInfolist;
use App\Filament\Resources\NeedRequests\Tables\NeedRequestsTable;
use App\Models\NeedRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class NeedRequestResource extends Resource
{
    protected static ?string $model = NeedRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Demandes';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'expression de besoin';

    protected static ?string $pluralModelLabel = 'expressions de besoin';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        $count = NeedRequest::query()->where('status', NeedRequestStatus::New)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return NeedRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NeedRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NeedRequestsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNeedRequests::route('/'),
            'view' => ViewNeedRequest::route('/{record}'),
            'edit' => EditNeedRequest::route('/{record}/edit'),
        ];
    }
}
