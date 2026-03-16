<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Service extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'salon_id',
        'name',
        'description',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('service_image')
            ->useDisk('public')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Max, 368, 232)
            ->performOnCollections('service_image')
            ->nonQueued();
    }

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
