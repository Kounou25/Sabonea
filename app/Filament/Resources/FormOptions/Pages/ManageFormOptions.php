<?php

namespace App\Filament\Resources\FormOptions\Pages;

use App\Filament\Resources\FormOptions\FormOptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFormOptions extends ManageRecords
{
    protected static string $resource = FormOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
