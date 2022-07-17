<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'origin', 'destination', 'description'];

    protected static function booted()
    {
        static::saved(function ($trip) {
            Cache::forever("trip@{$trip->id}", $trip);
        });

        static::deleted(function ($trip) {
            Cache::forget("trip@{$trip->id}");
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
