<?php

namespace App\Filament\Resources\ServiceTemplates\Pages;

use App\Filament\Resources\ServiceTemplates\ServiceTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceTemplate extends EditRecord
{
    protected static string $resource = ServiceTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
