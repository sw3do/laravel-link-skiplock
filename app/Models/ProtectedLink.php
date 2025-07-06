<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProtectedLink extends Model
{
    protected $fillable = [
        'title',
        'description',
        'short_code',
        'target_url',
        'youtube_channel_id',
        'user_id',
        'click_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'click_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->short_code) {
                $model->short_code = Str::random(8);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function youtubeChannel()
    {
        return $this->belongsTo(YouTubeChannel::class, 'youtube_channel_id');
    }

    public function getShortUrlAttribute()
    {
        return url('/l/' . $this->short_code);
    }
}
