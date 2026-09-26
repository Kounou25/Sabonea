<?php

namespace App\Filament\Resources\NeedRequests\Pages;

use App\Enums\NeedRequestStatus;
use App\Filament\Resources\NeedRequests\NeedRequestResource;
use App\Models\NeedRequest;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ListNeedRequests extends ListRecords
{
    protected static string $resource = NeedRequestResource::class;

    /**
     * Requests caught by the anti-spam trap are kept apart (a real one can be recovered from there).
     */
    public function getTabs(): array
    {
        $spam = NeedRequest::query()->where('status', NeedRequestStatus::Spam)->count();

        return [
            'inbox' => Tab::make('Demandes')
                ->icon(Heroicon::OutlinedInbox)
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', '!=', NeedRequestStatus::Spam)),
            'spam' => Tab::make('Spam probable')
                ->icon(Heroicon::OutlinedShieldExclamation)
                ->badge($spam ?: null)
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', NeedRequestStatus::Spam)),
        ];
    }
}
