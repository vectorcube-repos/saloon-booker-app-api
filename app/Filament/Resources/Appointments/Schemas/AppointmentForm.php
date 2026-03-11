<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Filament\Helpers\FilamentRoleHelper;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AppointmentForm
{
    public const STATUS_OPTIONS = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
        'completed' => 'Completed',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('salon_id')
                    ->label('Salon')
                    ->relationship('salon', 'name', function ($query) {
                        if (FilamentRoleHelper::isOwner()) {
                            $query->whereIn('id', FilamentRoleHelper::ownerSalonIds() ?: [0]);
                        }
                        if (FilamentRoleHelper::isStaff()) {
                            $provider = auth()->user()?->serviceProvider;
                            if ($provider) {
                                $query->where('id', $provider->salon_id);
                            }
                        }
                        return $query;
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Choose the service the customer booked. Global services are also listed.')
                    ->required(),
                Select::make('provider_id')
                    ->label('Service provider')
                    ->relationship('serviceProvider', 'display_name', function ($query) {
                        if (FilamentRoleHelper::isOwner()) {
                            $salonIds = FilamentRoleHelper::ownerSalonIds();
                            $query->whereIn('salon_id', $salonIds ?: [0]);
                        }
                        if (FilamentRoleHelper::isStaff()) {
                            $provider = auth()->user()?->serviceProvider;
                            if ($provider) {
                                $query->where('id', $provider->id);
                            }
                        }
                        return $query;
                    })
                    ->searchable()
                    ->preload()
                    ->placeholder('Unassigned')
                    ->helperText('Optional—leave blank to allow the salon to assign a provider.')
                    ->nullable(),
                Select::make('user_id')
                    ->label('Customer')
                    ->relationship('user', 'first_name')
                    ->searchable()
                    ->preload()
                    ->helperText('Customers are imported from the user catalog.')
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn (User $record): string => $record->getFilamentName() ?? $record->phone ?? "Customer #{$record->id}"),
                DateTimePicker::make('slot_start')
                    ->label('Start')
                    ->required()
                    ->withoutSeconds()
                    ->placeholder('Select start time'),
                DateTimePicker::make('slot_end')
                    ->label('End')
                    ->required()
                    ->withoutSeconds()
                    ->placeholder('Select end time'),
                Select::make('status')
                    ->label('Status')
                    ->options(self::STATUS_OPTIONS)
                    ->required()
                    ->default('confirmed'),
                Textarea::make('notes')
                    ->label('Notes')
                    ->columnSpanFull()
                    ->rows(3)
                    ->helperText('Optional memo for the staff.'),
            ]);
    }
}
