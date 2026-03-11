<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('salon_id')
                    ->label('Type')
                    ->helperText('Leave empty for global catalog. Select a salon for a private (salon-only) service.')
                    ->relationship('ownerSalon', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Global (catalog)'),
                TextInput::make('name')
                    ->required()
                    ->maxLength(120),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
