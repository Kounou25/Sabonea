<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Enums\ContactMessageStatus;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    /**
     * Opening a new message marks it as read.
     */
    public function mount(int|string $record): void
    {
        parent::mount($record);

        if ($this->getRecord()->status === ContactMessageStatus::New) {
            $this->getRecord()->update(['status' => ContactMessageStatus::Read]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Traiter le message'),
            DeleteAction::make(),
        ];
    }
}
