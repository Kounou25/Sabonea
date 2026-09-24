<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Enums\ContactMessageStatus;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

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
            Action::make('reply')
                ->label('Répondre par e-mail')
                ->icon(Heroicon::OutlinedPaperAirplane)
                ->url(fn (ContactMessage $record): string => 'mailto:'.$record->email.'?subject='.rawurlencode('Re: '.($record->subject?->t('label', $record->locale) ?? 'Sabonea'))),
            EditAction::make()->label('Traiter le message'),
            DeleteAction::make(),
        ];
    }
}
