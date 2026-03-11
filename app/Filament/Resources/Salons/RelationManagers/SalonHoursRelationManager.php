<?php

namespace App\Filament\Resources\Salons\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalonHoursRelationManager extends RelationManager
{
    protected static string $relationship = 'salonHours';

    protected static ?string $title = 'Hours';

    protected static array $dayNames = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('day_of_week')
                    ->label('Day')
                    ->options(self::$dayNames)
                    ->required(),
                TimePicker::make('open_time')
                    ->label('Opens at')
                    ->required(fn ($get) => ! $get('is_closed'))
                    ->seconds(false)
                    ->hidden(fn ($get) => $get('is_closed')),
                TimePicker::make('close_time')
                    ->label('Closes at')
                    ->required(fn ($get) => ! $get('is_closed'))
                    ->seconds(false)
                    ->hidden(fn ($get) => $get('is_closed')),
                Checkbox::make('is_closed')
                    ->label('Closed this day')
                    ->default(false)
                    ->live(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('day_of_week')
            ->columns([
                TextColumn::make('day_of_week')
                    ->formatStateUsing(fn ($state) => self::$dayNames[$state] ?? $state)
                    ->sortable(),
                TextColumn::make('open_time')
                    ->time()
                    ->placeholder('–'),
                TextColumn::make('close_time')
                    ->time()
                    ->placeholder('–'),
                IconColumn::make('is_closed')
                    ->boolean()
                    ->label('Closed'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        if (! empty($data['is_closed'])) {
                            $data['open_time'] = $data['open_time'] ?? '09:00';
                            $data['close_time'] = $data['close_time'] ?? '18:00';
                        }
                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        if (! empty($data['is_closed'])) {
                            $data['open_time'] = $data['open_time'] ?? '09:00';
                            $data['close_time'] = $data['close_time'] ?? '18:00';
                        }
                        return $data;
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('day_of_week');
    }
}
