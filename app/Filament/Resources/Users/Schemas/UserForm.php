<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public const ROLE_OPTIONS = [
        'customer' => 'Customer',
        'owner' => 'Owner',
        'staff' => 'Staff',
        'admin' => 'Admin',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('first_name'),
                TextInput::make('last_name'),
                Select::make('role')
                    ->options(self::ROLE_OPTIONS)
                    ->default('customer')
                    ->required(),
                TextInput::make('password_hash')
                    ->label('Password')
                    ->password()
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn ($livewire) => ! optional($livewire)->getRecord())
                    ->columnSpanFull(),
            ]);
    }
}
