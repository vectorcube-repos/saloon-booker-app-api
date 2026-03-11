<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Filament\Helpers\FilamentRoleHelper;
use App\Filament\Resources\Appointments\Schemas\AppointmentForm;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('slot_start', 'desc')
            ->columns([
                TextColumn::make('slot_start')
                    ->label('Start')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('slot_end')
                    ->label('End')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('salon.name')
                    ->label('Salon')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('serviceProvider.display_name')
                    ->label('Provider')
                    ->placeholder('Unassigned')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.first_name')
                    ->label('Customer')
                    ->formatStateUsing(fn ($state, $record) => $record?->user?->getFilamentName() ?? ($record?->user?->phone ?? '–'))
                    ->placeholder('–')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => AppointmentForm::STATUS_OPTIONS[$state] ?? ($state ? ucfirst($state) : '–'))
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'cancelled',
                        'primary' => 'completed',
                    ])
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Booked at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(AppointmentForm::STATUS_OPTIONS),
                SelectFilter::make('salon_id')
                    ->relationship('salon', 'name', function ($query) {
                        if (FilamentRoleHelper::isOwner()) {
                            $query->whereIn('id', FilamentRoleHelper::ownerSalonIds() ?: [0]);
                        }
                        if (FilamentRoleHelper::isStaff()) {
                            $provider = Auth::user()?->serviceProvider;
                            if ($provider) {
                                $query->where('id', $provider->salon_id);
                            }
                        }
                        return $query;
                    })
                    ->label('Salon')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('service_id')
                    ->relationship('service', 'name', function ($query) {
                        if (FilamentRoleHelper::isOwner()) {
                            $query->where(fn ($q) => $q->whereNull('salon_id')->orWhereIn('salon_id', FilamentRoleHelper::ownerSalonIds() ?: [0]));
                        }
                        if (FilamentRoleHelper::isStaff()) {
                            $provider = Auth::user()?->serviceProvider;
                            if ($provider) {
                                $query->where(fn ($q) => $q->whereNull('salon_id')->orWhere('salon_id', $provider->salon_id));
                            }
                        }
                        return $query;
                    })
                    ->label('Service')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('provider_id')
                    ->relationship('serviceProvider', 'display_name', function ($query) {
                        if (FilamentRoleHelper::isOwner()) {
                            $query->whereIn('salon_id', FilamentRoleHelper::ownerSalonIds() ?: [0]);
                        }
                        if (FilamentRoleHelper::isStaff()) {
                            $provider = Auth::user()?->serviceProvider;
                            if ($provider) {
                                $query->where('id', $provider->id);
                            }
                        }
                        return $query;
                    })
                    ->label('Provider')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
