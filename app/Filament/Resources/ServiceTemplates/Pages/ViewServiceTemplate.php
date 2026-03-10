<?php

namespace App\Filament\Resources\ServiceTemplates\Pages;

use App\Filament\Resources\ServiceTemplates\ServiceTemplateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceTemplate extends ViewRecord
{
    protected static string $resource = ServiceTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
