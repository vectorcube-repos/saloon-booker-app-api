<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'salon_id',
        'name',
        'description',
    ];

    public function ownerSalon(): BelongsTo
    {
        return $this->belongsTo(Salon::class, 'salon_id');
    }

    public function scopeGlobal(Builder $query): Builder
    {
        return $query->whereNull('salon_id');
    }

    public function scopePrivateTo(Builder $query, int $salonId): Builder
    {
        return $query->where('salon_id', $salonId);
    }

    public function isGlobal(): bool
    {
        return $this->salon_id === null;
    }

    public function isPrivate(): bool
    {
        return $this->salon_id !== null;
    }

    public function salons(): BelongsToMany
    {
        return $this->belongsToMany(Salon::class, 'salon_service')
            ->withPivot(['duration_minutes', 'rate', 'is_active'])
            ->withTimestamps();
    }

    public function serviceProviders(): BelongsToMany
    {
        return $this->belongsToMany(ServiceProvider::class, 'provider_services', 'service_id', 'provider_id');
    }

    public function appointments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
