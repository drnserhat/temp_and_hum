<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Measurement extends Model
{
    protected $fillable = [
        'device_id',
        'temperature',
        'humidity',
        'measured_at',
    ];

    protected $casts = [
        'measured_at' => 'datetime',
        'temperature' => 'float',
        'humidity' => 'float',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
