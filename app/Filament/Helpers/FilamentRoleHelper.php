<?php

namespace App\Filament\Helpers;

use App\Models\User;

class FilamentRoleHelper
{
    public static function isAdmin(?User $user = null): bool
    {
        $user ??= auth()->user();

        return $user?->role === 'admin';
    }

    public static function isOwner(?User $user = null): bool
    {
        $user ??= auth()->user();

        return $user?->role === 'owner';
    }

    public static function isStaff(?User $user = null): bool
    {
        $user ??= auth()->user();

        return $user?->role === 'staff';
    }

    public static function ownerSalonIds(?User $user = null): array
    {
        $user ??= auth()->user();

        if (! $user || $user->role !== 'owner') {
            return [];
        }

        return $user->ownedSalons()->pluck('id')->toArray();
    }

    public static function staffProviderId(?User $user = null): ?int
    {
        $user ??= auth()->user();

        if (! $user || $user->role !== 'staff') {
            return null;
        }

        return $user->serviceProvider?->id;
    }
}
