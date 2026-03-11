<?php

namespace App\Filament\Resources\ServiceProviders\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ServiceProviderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('salon.name')
                    ->label('Salon'),
                TextEntry::make('display_name'),
                TextEntry::make('user')
                    ->label('Linked user')
                    ->formatStateUsing(fn ($state) => $state ? $state->getFilamentName() . ' (' . $state->phone . ')' : '–')
                    ->placeholder('–'),
                TextEntry::make('bio')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('skill_tags')
                    ->badge()
                    ->separator(','),
                TextEntry::make('services_count')
                    ->label('Services')
                    ->state(fn ($record) => $record ? $record->services()->count() : 0),
                IconEntry::make('active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
