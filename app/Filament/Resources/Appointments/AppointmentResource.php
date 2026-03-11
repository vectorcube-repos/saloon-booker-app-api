<?php

namespace App\Filament\Resources\Appointments;

use App\Filament\Helpers\FilamentRoleHelper;
use App\Filament\Resources\Appointments\Pages\CreateAppointment;
use App\Filament\Resources\Appointments\Pages\EditAppointment;
use App\Filament\Resources\Appointments\Pages\ListAppointments;
use App\Filament\Resources\Appointments\Pages\ViewAppointment;
use App\Filament\Resources\Appointments\Schemas\AppointmentForm;
use App\Filament\Resources\Appointments\Schemas\AppointmentInfolist;
use App\Filament\Resources\Appointments\Tables\AppointmentsTable;
use App\Models\Appointment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Salons';

    protected static ?string $navigationLabel = 'Appointments';

    protected static ?string $recordTitleAttribute = 'slot_start';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    public static function canViewAny(): bool
    {
        return FilamentRoleHelper::isAdmin() || FilamentRoleHelper::isOwner() || FilamentRoleHelper::isStaff();
    }

    public static function canEdit($record): bool
    {
        if (FilamentRoleHelper::isAdmin()) {
            return true;
        }
        if (FilamentRoleHelper::isOwner()) {
            return in_array($record->salon_id, FilamentRoleHelper::ownerSalonIds());
        }
        if (FilamentRoleHelper::isStaff()) {
            return $record->provider_id === FilamentRoleHelper::staffProviderId();
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
        return AppointmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AppointmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AppointmentsTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (FilamentRoleHelper::isOwner()) {
            $salonIds = FilamentRoleHelper::ownerSalonIds();
            $query->whereIn('salon_id', $salonIds ?: [0]);
        }

        if (FilamentRoleHelper::isStaff()) {
            $providerId = FilamentRoleHelper::staffProviderId();
            $query->where('provider_id', $providerId ?? 0);
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
            'index' => ListAppointments::route('/'),
            'create' => CreateAppointment::route('/create'),
            'view' => ViewAppointment::route('/{record}'),
            'edit' => EditAppointment::route('/{record}/edit'),
        ];
    }
}
