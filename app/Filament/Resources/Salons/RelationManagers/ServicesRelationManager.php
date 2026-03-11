<?php

namespace App\Filament\Resources\Salons\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'services';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('duration_minutes')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->suffix('min'),
                TextInput::make('rate')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->suffix('¢'),
                Checkbox::make('is_active')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('duration_minutes')
                    ->numeric()
                    ->suffix(' min')
                    ->sortable(),
                TextColumn::make('rate')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => $state !== null ? '$' . number_format($state / 100, 2) : '–')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->recordSelectOptionsQuery(fn ($query) => $query->where(function ($q) {
                        $salonId = $this->getOwnerRecord()->id;
                        $q->whereNull('salon_id')
                            ->orWhere('salon_id', $salonId);
                    }))
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('duration_minutes')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(60)
                            ->suffix('min'),
                        TextInput::make('rate')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->suffix('¢'),
                        Checkbox::make('is_active')
                            ->default(true),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
