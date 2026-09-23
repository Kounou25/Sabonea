<?php

namespace App\Filament\Resources\NeedRequests\Pages;

use App\Filament\Resources\NeedRequests\NeedRequestResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditNeedRequest extends EditRecord
{
    protected static string $resource = NeedRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
