<?php

namespace App\Filament\Resources\SupplierApplications\Pages;

use App\Filament\Resources\SupplierApplications\SupplierApplicationResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSupplierApplication extends EditRecord
{
    protected static string $resource = SupplierApplicationResource::class;

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
