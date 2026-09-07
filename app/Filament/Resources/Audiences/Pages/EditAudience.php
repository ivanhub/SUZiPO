<?php

namespace App\Filament\Resources\Audiences\Pages;

use App\Filament\Resources\Audiences\AudienceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAudience extends EditRecord
{
    protected static string $resource = AudienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
