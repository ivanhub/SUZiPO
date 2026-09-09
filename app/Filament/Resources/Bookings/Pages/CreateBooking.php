<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Если передан параметр даты из календаря
        if (request()->has('date')) {
            $data['start_date'] = request()->get('date');
            $data['end_date'] = request()->get('date');
        }
        
        return $data;
    }
}
