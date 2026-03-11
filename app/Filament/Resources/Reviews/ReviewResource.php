<?php

namespace App\Filament\Resources\Reviews;

use App\Filament\Helpers\FilamentRoleHelper;
use App\Filament\Resources\Reviews\Pages\ListReviews;
use App\Filament\Resources\Reviews\Pages\ViewReview;
use App\Filament\Resources\Reviews\Schemas\ReviewInfolist;
use App\Filament\Resources\Reviews\Tables\ReviewsTable;
use App\Models\Review;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Salons';

    protected static ?string $navigationLabel = 'Reviews';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    public static function canViewAny(): bool
    {
        return FilamentRoleHelper::isAdmin() || FilamentRoleHelper::isOwner();
    }

    public static function canView($record): bool
    {
        if (FilamentRoleHelper::isAdmin()) {
            return true;
        }
        if (FilamentRoleHelper::isOwner()) {
            return in_array($record->salon_id, FilamentRoleHelper::ownerSalonIds());
        }
        return false;
    }

    public static function canDelete($record): bool
    {
        return static::canView($record);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReviewInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReviewsTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery()->with(['salon', 'user', 'appointment']);

        if (FilamentRoleHelper::isOwner()) {
            $salonIds = FilamentRoleHelper::ownerSalonIds();
            $query->whereIn('salon_id', $salonIds ?: [0]);
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReviews::route('/'),
            'view' => ViewReview::route('/{record}'),
        ];
    }
}
