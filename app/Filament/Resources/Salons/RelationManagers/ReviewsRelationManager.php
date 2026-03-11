<?php

namespace App\Filament\Resources\Salons\RelationManagers;

use App\Filament\Resources\Reviews\ReviewResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('rating')
            ->defaultSort('created_at', 'desc')
            ->columns([
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
                TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record) => ReviewResource::getUrl('view', ['record' => $record])),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
