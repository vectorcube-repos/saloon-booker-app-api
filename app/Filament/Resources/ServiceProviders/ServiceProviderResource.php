<?php

namespace App\Filament\Resources\ServiceProviders;

use App\Filament\Helpers\FilamentRoleHelper;
use App\Filament\Resources\ServiceProviders\Pages\CreateServiceProvider;
use App\Filament\Resources\ServiceProviders\Pages\EditServiceProvider;
use App\Filament\Resources\ServiceProviders\Pages\ListServiceProviders;
use App\Filament\Resources\ServiceProviders\Pages\ViewServiceProvider;
use App\Filament\Resources\ServiceProviders\Schemas\ServiceProviderForm;
use App\Filament\Resources\ServiceProviders\Schemas\ServiceProviderInfolist;
use App\Filament\Resources\ServiceProviders\Tables\ServiceProvidersTable;
use App\Models\ServiceProvider;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceProviderResource extends Resource
{
    protected static ?string $model = ServiceProvider::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Salons';

    protected static ?string $recordTitleAttribute = 'display_name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

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
            return in_array($record->salon_id, FilamentRoleHelper::ownerSalonIds());
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
        return ServiceProviderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceProviderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceProvidersTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (FilamentRoleHelper::isOwner()) {
            $salonIds = FilamentRoleHelper::ownerSalonIds();
            $query->whereIn('salon_id', $salonIds ?: [0]);
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ServicesRelationManager::class,
            RelationManagers\AppointmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServiceProviders::route('/'),
            'create' => CreateServiceProvider::route('/create'),
            'view' => ViewServiceProvider::route('/{record}'),
            'edit' => EditServiceProvider::route('/{record}/edit'),
        ];
    }
}
