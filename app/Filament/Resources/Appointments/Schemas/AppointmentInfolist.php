<?php

namespace App\Filament\Resources\Appointments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AppointmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('salon.name')
                    ->label('Salon')
                    ->placeholder('–'),
                TextEntry::make('service.name')
                    ->label('Service')
                    ->placeholder('–'),
                TextEntry::make('serviceProvider.display_name')
                    ->label('Service provider')
                    ->placeholder('Unassigned'),
                TextEntry::make('user')
                    ->label('Customer')
                    ->formatStateUsing(fn ($state, $record) => $record?->user?->getFilamentName() ?? '–')
                    ->placeholder('–'),
                TextEntry::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (?string $state) => $state ? ucfirst($state) : '–')
                    ->placeholder('–'),
                TextEntry::make('slot_start')
                    ->label('Start')
                    ->dateTime()
                    ->placeholder('–'),
                TextEntry::make('slot_end')
                    ->label('End')
                    ->dateTime()
                    ->placeholder('–'),
                TextEntry::make('notes')
                    ->label('Notes')
                    ->columnSpanFull()
                    ->placeholder('–'),
                TextEntry::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->placeholder('–'),
                TextEntry::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->placeholder('–'),
            ]);
    }
}
