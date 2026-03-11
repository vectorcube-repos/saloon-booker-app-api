<?php

namespace App\Filament\Resources\Reviews\Tables;

use App\Filament\Helpers\FilamentRoleHelper;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('salon.name')
                    ->label('Salon')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user')
                    ->label('User')
                    ->formatStateUsing(fn ($record) => $record->user?->getFilamentName() ?? $record->user?->phone ?? '–')
                    ->placeholder('–'),
                TextColumn::make('rating')
                    ->badge()
                    ->sortable(),
                TextColumn::make('comment')
                    ->limit(40)
                    ->placeholder('–')
                    ->wrap(),
                TextColumn::make('appointment_id')
                    ->label('Appointment')
                    ->formatStateUsing(fn ($record) => $record->appointment ? "#{$record->appointment_id}" : '–')
                    ->placeholder('–')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('salon_id')
                    ->relationship('salon', 'name', fn ($query) => FilamentRoleHelper::isOwner()
                        ? $query->whereIn('id', FilamentRoleHelper::ownerSalonIds() ?: [0])
                        : $query)
                    ->label('Salon')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('rating')
                    ->options([
                        1 => '1 star',
                        2 => '2 stars',
                        3 => '3 stars',
                        4 => '4 stars',
                        5 => '5 stars',
                    ]),
                TernaryFilter::make('has_comment')
                    ->label('Has comment')
                    ->placeholder('All')
                    ->trueLabel('With comment')
                    ->falseLabel('No comment')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('comment')->where('comment', '!=', ''),
                        false: fn ($query) => $query->where(fn ($q) => $q->whereNull('comment')->orWhere('comment', '')),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
