<?php

namespace App\Filament\Widgets;

use App\Filament\Helpers\FilamentRoleHelper;
use App\Filament\Resources\Appointments\AppointmentResource;
use App\Filament\Resources\Appointments\Schemas\AppointmentForm;
use App\Models\Appointment;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentAppointmentsTable extends TableWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Recent appointments';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $query = Appointment::query()->with(['salon', 'service', 'user', 'serviceProvider']);

        if (FilamentRoleHelper::isOwner()) {
            $salonIds = FilamentRoleHelper::ownerSalonIds();
            $query->whereIn('salon_id', $salonIds ?: [0]);
        }

        if (FilamentRoleHelper::isStaff()) {
            $providerId = FilamentRoleHelper::staffProviderId();
            $query->where('provider_id', $providerId ?? 0);
        }

        return $table
            ->query(fn (): Builder => $query->latest('slot_start'))
            ->defaultSort('slot_start', 'desc')
            ->columns([
                TextColumn::make('slot_start')
                    ->label('Start')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('salon.name')
                    ->label('Salon')
                    ->searchable(),
                TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable(),
                TextColumn::make('user')
                    ->label('Customer')
                    ->formatStateUsing(fn ($record) => $record->user?->getFilamentName() ?? $record->user?->phone ?? '–')
                    ->placeholder('–'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => AppointmentForm::STATUS_OPTIONS[$state] ?? ($state ? ucfirst($state) : '–'))
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'cancelled',
                        'primary' => 'completed',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn (Appointment $record) => AppointmentResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
