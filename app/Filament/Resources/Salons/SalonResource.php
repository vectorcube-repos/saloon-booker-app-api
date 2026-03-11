<?php

namespace App\Filament\Resources\Salons;

use App\Filament\Helpers\FilamentRoleHelper;
use App\Filament\Resources\Salons\Pages\CreateSalon;
use App\Filament\Resources\Salons\Pages\EditSalon;
use App\Filament\Resources\Salons\Pages\ListSalons;
use App\Filament\Resources\Salons\Pages\ViewSalon;
use App\Filament\Resources\Salons\Schemas\SalonForm;
use App\Filament\Resources\Salons\Schemas\SalonInfolist;
use App\Filament\Resources\Salons\Tables\SalonsTable;
use App\Models\Salon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalonResource extends Resource
{
    protected static ?string $model = Salon::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Salons';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

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
            return in_array($record->id, FilamentRoleHelper::ownerSalonIds());
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
        return SalonForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SalonInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalonsTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery()->with(['owner']);

        if (FilamentRoleHelper::isOwner()) {
            $salonIds = FilamentRoleHelper::ownerSalonIds();
            $query->whereIn('id', $salonIds ?: [0]);
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SalonHoursRelationManager::class,
            RelationManagers\ServicesRelationManager::class,
            RelationManagers\AppointmentsRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSalons::route('/'),
            'create' => CreateSalon::route('/create'),
            'view' => ViewSalon::route('/{record}'),
            'edit' => EditSalon::route('/{record}/edit'),
        ];
    }
}
