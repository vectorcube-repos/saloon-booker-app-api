<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReviewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('salon.name')
                    ->label('Salon'),
                TextEntry::make('user')
                    ->label('User')
                    ->formatStateUsing(fn ($record) => $record->user?->getFilamentName() ?? $record->user?->phone ?? '–')
                    ->placeholder('–'),
                TextEntry::make('rating')
                    ->badge(),
                TextEntry::make('appointment_id')
                    ->label('Appointment')
                    ->formatStateUsing(fn ($record) => $record->appointment ? "#{$record->appointment_id}" : '–')
                    ->placeholder('–'),
                TextEntry::make('comment')
                    ->placeholder('–')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime(),
            ]);
    }
}
