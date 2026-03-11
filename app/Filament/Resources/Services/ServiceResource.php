<?php

namespace App\Filament\Resources\Services;

use App\Filament\Helpers\FilamentRoleHelper;
use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\Services\Pages\ViewService;
use App\Filament\Resources\Services\Schemas\ServiceForm;
use App\Filament\Resources\Services\Schemas\ServiceInfolist;
use App\Filament\Resources\Services\Tables\ServicesTable;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Salons';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScissors;

    public static function canViewAny(): bool
    {
        return FilamentRoleHelper::isAdmin() || FilamentRoleHelper::isOwner();
    }

    public static function canEdit($record): bool
    {
        if (FilamentRoleHelper::isAdmin()) {
            return true;
        }
        if (FilamentRoleHelper::isOwner()) {
            return $record->salon_id === null || in_array($record->salon_id, FilamentRoleHelper::ownerSalonIds());
        }
        return false;
    }

    public static function canDelete($record): bool
    {
        return static::canEdit($record);
    }

    public static function canView($record): bool
    {
        return static::canEdit($record);
    }

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery()->with(['ownerSalon']);

        if (FilamentRoleHelper::isOwner()) {
            $salonIds = FilamentRoleHelper::ownerSalonIds();
            $query->where(function ($q) use ($salonIds) {
                $q->whereNull('salon_id')
                    ->orWhereIn('salon_id', $salonIds ?: [0]);
            });
        }

        return $query;
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
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'view' => ViewService::route('/{record}'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
