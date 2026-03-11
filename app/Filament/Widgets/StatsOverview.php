<?php

namespace App\Filament\Widgets;

use App\Filament\Helpers\FilamentRoleHelper;
use App\Models\Appointment;
use App\Models\Salon;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $weekStart = Carbon::today()->startOfWeek();

        $appointmentsQuery = Appointment::query();
        $salonsQuery = Salon::query();

        if (FilamentRoleHelper::isOwner()) {
            $salonIds = FilamentRoleHelper::ownerSalonIds();
            $appointmentsQuery->whereIn('salon_id', $salonIds ?: [0]);
            $salonsQuery->whereIn('id', $salonIds ?: [0]);
        }

        if (FilamentRoleHelper::isStaff()) {
            $providerId = FilamentRoleHelper::staffProviderId();
            $appointmentsQuery->where('provider_id', $providerId ?? 0);
        }

        $stats = [];

        if (! FilamentRoleHelper::isStaff()) {
            $stats[] = Stat::make('Total salons', $salonsQuery->count())
                ->description('Registered salons')
                ->descriptionIcon('heroicon-o-building-storefront');
        }

        $stats[] = Stat::make('Appointments today', (clone $appointmentsQuery)->whereDate('slot_start', $today)->count())
            ->description(FilamentRoleHelper::isStaff() ? 'Your appointments today' : 'Scheduled for today')
            ->descriptionIcon('heroicon-o-calendar-days');
        $stats[] = Stat::make('Appointments this week', (clone $appointmentsQuery)->where('slot_start', '>=', $weekStart)->count())
            ->description(FilamentRoleHelper::isStaff() ? 'Your appointments this week' : 'Scheduled this week')
            ->descriptionIcon('heroicon-o-calendar');

        if (FilamentRoleHelper::isAdmin()) {
            $stats[] = Stat::make('Total users', User::count())
                ->description('Customers: ' . User::where('role', 'customer')->count() . ' | Staff: ' . User::whereIn('role', ['owner', 'staff', 'admin'])->count())
                ->descriptionIcon('heroicon-o-users');
        }

        return $stats;
    }
}
