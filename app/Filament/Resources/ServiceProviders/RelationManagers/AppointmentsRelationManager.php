<?php

namespace App\Filament\Resources\ServiceProviders\RelationManagers;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Filament\Resources\Appointments\Schemas\AppointmentForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AppointmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'appointments';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('slot_start')
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
            ->headerActions([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record) => AppointmentResource::getUrl('view', ['record' => $record])),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
