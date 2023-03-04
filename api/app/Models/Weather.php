<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Weather extends Model
{
    use HasFactory;

    const CACHE_KEY = "weather_data";
    const TYPES = [
        "current",
        "forecast",
    ];

    protected $fillable = [
        "type",
        "weather",
        "main",
        "wind",
        "rain",
        "clouds",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
