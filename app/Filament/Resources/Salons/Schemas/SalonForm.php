<?php

namespace App\Filament\Resources\Salons\Schemas;

use App\Filament\Helpers\FilamentRoleHelper;
use Fahiem\FilamentPinpoint\Pinpoint;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class SalonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('owner_id')
                    ->relationship('owner', 'phone', function ($query) {
                        $query->where('role', 'owner');
                        if (FilamentRoleHelper::isOwner()) {
                            $userId = Auth::id();
                            if ($userId !== null) {
                                $query->where('id', $userId);
                            }
                        }
                        return $query;
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->getFilamentName() . ' (' . $record->phone . ')')
                    ->searchable(['first_name', 'last_name', 'phone'])
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->required(),
                SpatieMediaLibraryFileUpload::make('salon_image')
                    ->label('Salon image')
                    ->collection('salon_image')
                    ->conversion('thumb')
                    ->image()
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                    ->imagePreviewHeight('200')
                    ->columnSpanFull()
                    ->openable()
                    ->helperText('Upload a photo for this salon. Recommended: square image, min 200×200px.'), 
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('phone')
                    ->tel(),
                Textarea::make('address')
                    ->columnSpanFull(),
                TextInput::make('city'),
                TextInput::make('state'),
                TextInput::make('postal_code'),
                Pinpoint::make('location')
                    ->label('Choose location on map')
                    ->latField('latitude')
                    ->lngField('longitude')
                    ->addressField('address')
                    ->cityField('city')
                    ->provinceField('state')
                    ->postalCodeField('postal_code')
                    ->defaultZoom(14)
                    ->height(400)
                    ->columnSpanFull()
                    ->dehydrated(false),
                TextInput::make('latitude')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(),
                TextInput::make('longitude')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(),
                Select::make('status')
                    ->options(['active' => 'Active', 'closed' => 'Closed'])
                    ->default('active')
                    ->required(),
            ]);
    }
}
