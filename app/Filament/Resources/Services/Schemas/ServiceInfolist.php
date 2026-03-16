<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ServiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryImageEntry::make('service_image')
                    ->label('Service image')
                    ->collection('service_image')
                    ->conversion('card')
                    ->hidden(fn ($record) => $record && $record->getFirstMedia('service_image') === null),
                TextEntry::make('ownerSalon')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => $state ? "Private ({$state->name})" : 'Global')
                    ->placeholder('Global'),
                TextEntry::make('name'),
                TextEntry::make('salons_count')
                    ->label('Salons')
                    ->state(fn ($record) => $record ? $record->salons()->count() : 0),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
