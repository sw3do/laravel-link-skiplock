<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YouTubeChannel extends Model
{
    protected $table = 'youtube_channels';
    
    protected $fillable = [
        'channel_id',
        'channel_name',
        'channel_url',
        'channel_thumbnail',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function protectedLinks()
    {
        return $this->hasMany(ProtectedLink::class, 'youtube_channel_id');
    }
}
