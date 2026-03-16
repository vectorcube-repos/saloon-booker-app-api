<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Helpers\FilamentRoleHelper;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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
                    ->relationship('ownerSalon', 'name', function ($query) {
                        if (FilamentRoleHelper::isOwner()) {
                            $query->whereIn('id', FilamentRoleHelper::ownerSalonIds() ?: [0]);
                        }
                        return $query;
                    })
                    ->searchable()
                    ->preload()
                    ->placeholder('Global (catalog)'),
                TextInput::make('name')
                    ->required()
                    ->maxLength(120),
                Textarea::make('description')
                    ->columnSpanFull(),
                SpatieMediaLibraryFileUpload::make('service_image')
                    ->collection('service_image')
                    ->conversion('thumb')
                    ->image()
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                    ->imagePreviewHeight('200')
                    ->columnSpanFull()
                    ->openable()
                    ->helperText('Upload a photo for this doctor. Recommended: square image, min 200×200px.'),                    
            ]);
    }
}
