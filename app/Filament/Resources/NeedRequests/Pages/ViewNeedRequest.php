<?php

namespace App\Filament\Resources\NeedRequests\Pages;

use App\Filament\Resources\NeedRequests\NeedRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNeedRequest extends ViewRecord
{
    protected static string $resource = NeedRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Traiter la demande'),
            DeleteAction::make(),
        ];
    }
}
