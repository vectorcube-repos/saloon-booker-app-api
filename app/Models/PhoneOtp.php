<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneOtp extends Model
{
    protected $table = 'phone_otps';

    protected $fillable = [
        'phone',
        'otp_code',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function isValidFor(string $otp): bool
    {
        return $this->otp_code === $otp && $this->expires_at->isFuture();
    }

    public static function findValidForPhone(string $phone, string $otp): ?self
    {
        $record = self::where('phone', $phone)->first();

        return $record && $record->isValidFor($otp) ? $record : null;
    }
}
