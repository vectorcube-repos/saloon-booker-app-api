<?php

namespace App\Filament\Resources\ServiceTemplates;

use App\Filament\Resources\ServiceTemplates\Pages\CreateServiceTemplate;
use App\Filament\Resources\ServiceTemplates\Pages\EditServiceTemplate;
use App\Filament\Resources\ServiceTemplates\Pages\ListServiceTemplates;
use App\Filament\Resources\ServiceTemplates\Pages\ViewServiceTemplate;
use App\Filament\Resources\ServiceTemplates\Schemas\ServiceTemplateForm;
use App\Filament\Resources\ServiceTemplates\Schemas\ServiceTemplateInfolist;
use App\Filament\Resources\ServiceTemplates\Tables\ServiceTemplatesTable;
use App\Models\ServiceTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceTemplateResource extends Resource
{
    protected static ?string $model = ServiceTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ServiceTemplateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceTemplateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceTemplatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServiceTemplates::route('/'),
            'create' => CreateServiceTemplate::route('/create'),
            'view' => ViewServiceTemplate::route('/{record}'),
            'edit' => EditServiceTemplate::route('/{record}/edit'),
        ];
    }
}
