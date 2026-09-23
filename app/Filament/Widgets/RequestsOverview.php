<?php

namespace App\Filament\Widgets;

use App\Enums\ContactMessageStatus;
use App\Enums\NeedRequestStatus;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\NeedRequests\NeedRequestResource;
use App\Models\ContactMessage;
use App\Models\NeedRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RequestsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -1;

    protected function getStats(): array
    {
        return [
            Stat::make('Nouvelles expressions de besoin', NeedRequest::query()->where('status', NeedRequestStatus::New)->count())
                ->description('À analyser')
                ->color('warning')
                ->url(NeedRequestResource::getUrl('index')),
            Stat::make('Demandes en cours d\'analyse', NeedRequest::query()->where('status', NeedRequestStatus::InReview)->count())
                ->color('info')
                ->url(NeedRequestResource::getUrl('index')),
            Stat::make('Messages de contact non lus', ContactMessage::query()->where('status', ContactMessageStatus::New)->count())
                ->color('warning')
                ->url(ContactMessageResource::getUrl('index')),
        ];
    }
}
