<?php

namespace App\Filament\Resources\ServiceProviders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServiceProvidersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('salon.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('display_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user')
                    ->label('Linked user')
                    ->formatStateUsing(fn ($state) => $state ? $state->getFilamentName() . ' (' . $state->phone . ')' : '–')
                    ->placeholder('–'),
                TextColumn::make('skill_tags')
                    ->badge()
                    ->separator(','),
                TextColumn::make('services_count')
                    ->label('Services')
                    ->counts('services')
                    ->sortable(),
                IconColumn::make('active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('salon_id')
                    ->relationship('salon', 'name')
                    ->label('Salon')
                    ->searchable()
                    ->preload(),
                \Filament\Tables\Filters\TernaryFilter::make('active')
                    ->placeholder('All')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
