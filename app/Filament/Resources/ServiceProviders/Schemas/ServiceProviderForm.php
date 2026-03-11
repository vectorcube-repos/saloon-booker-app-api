<?php

namespace App\Filament\Resources\ServiceProviders\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceProviderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('salon_id')
                    ->relationship('salon', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('user_id')
                    ->label('Linked user (staff)')
                    ->helperText('Optional. Link to a staff user account.')
                    ->options(fn () => User::where('role', 'staff')->get()->mapWithKeys(fn ($u) => [$u->id => $u->getFilamentName() . ' (' . $u->phone . ')']))
                    ->searchable(['first_name', 'last_name', 'phone'])
                    ->placeholder('None'),
                TextInput::make('display_name')
                    ->required()
                    ->maxLength(120),
                Textarea::make('bio')
                    ->columnSpanFull(),
                TagsInput::make('skill_tags')
                    ->placeholder('Add skill (e.g. hair, nails)')
                    ->suggestions(['hair', 'nails', 'skincare', 'massage', 'color', 'styling', 'mens', 'bridal']),
                Toggle::make('active')
                    ->default(true),
            ]);
    }
}
