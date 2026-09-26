<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Enums\ContactMessageStatus;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ListContactMessages extends ListRecords
{
    protected static string $resource = ContactMessageResource::class;

    /**
     * Messages caught by the anti-spam trap are kept apart (a real one can be recovered from there).
     */
    public function getTabs(): array
    {
        $spam = ContactMessage::query()->where('status', ContactMessageStatus::Spam)->count();

        return [
            'inbox' => Tab::make('Messages')
                ->icon(Heroicon::OutlinedInbox)
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', '!=', ContactMessageStatus::Spam)),
            'spam' => Tab::make('Spam probable')
                ->icon(Heroicon::OutlinedShieldExclamation)
                ->badge($spam ?: null)
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', ContactMessageStatus::Spam)),
        ];
    }
}
