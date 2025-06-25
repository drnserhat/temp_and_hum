<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Device extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'serial_number',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }

    public function getLatestMeasurement()
    {
        return $this->measurements()->latest('measured_at')->first();
    }

    public function userDevice(): HasOne
    {
        return $this->hasOne(UserDevice::class);
    }

    public static function generateSerialNumber(): string
    {
        do {
            $serialNumber = 'DEVICE' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('serial_number', $serialNumber)->exists());
        
        return $serialNumber;
    }
}
