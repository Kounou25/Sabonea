<?php

namespace App\Filament\Resources\NeedRequests\Pages;

use App\Enums\NeedRequestStatus;
use App\Filament\Resources\NeedRequests\NeedRequestResource;
use App\Models\NeedRequest;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewNeedRequest extends ViewRecord
{
    protected static string $resource = NeedRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('notSpam')
                ->label('Ce n\'est pas un spam')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('success')
                ->visible(fn (NeedRequest $record): bool => $record->status === NeedRequestStatus::Spam)
                ->action(function (NeedRequest $record): void {
                    $record->update(['status' => NeedRequestStatus::New]);
                    Notification::make()->title('Demande remise avec les autres demandes')->success()->send();
                }),
            EditAction::make()->label('Traiter la demande'),
            DeleteAction::make(),
        ];
    }
}
