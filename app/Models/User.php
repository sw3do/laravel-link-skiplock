<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'youtube_subscriptions',
        'subscriptions_updated_at',
        'google_access_token',
        'google_refresh_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_access_token',
        'google_refresh_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'subscriptions_updated_at' => 'datetime',
            'password' => 'hashed',
            'youtube_subscriptions' => 'array',
        ];
    }

    public function youtubeChannels()
    {
        return $this->hasMany(YouTubeChannel::class, 'user_id');
    }

    public function protectedLinks()
    {
        return $this->hasMany(ProtectedLink::class, 'user_id');
    }

    /**
     * Check if the user is subscribed to a specific YouTube channel
     */
    public function isSubscribedToChannel($channelId)
    {
        if (!$this->youtube_subscriptions) {
            return false;
        }

        return in_array($channelId, $this->youtube_subscriptions);
    }

    /**
     * Check if the user's subscriptions are stale (24 hours)
     */
    public function areSubscriptionsStale()
    {
        if (!$this->subscriptions_updated_at) {
            return true;
        }

        return $this->subscriptions_updated_at->lt(now()->subDay());
    }

    /**
     * Check if the user has YouTube access
     */
    public function hasYouTubeAccess()
    {
        return $this->google_access_token && $this->subscriptions_updated_at !== null;
    }

    /**
     * Check if the user has any subscriptions
     */
    public function hasSubscriptions()
    {
        return $this->youtube_subscriptions && is_array($this->youtube_subscriptions) && count($this->youtube_subscriptions) > 0;
    }

    /**
     * Get detailed YouTube status
     */
    public function getYouTubeStatus()
    {
        if (!$this->google_access_token) {
            return 'no_token'; // Not connected to Google
        }
        
        if (!$this->subscriptions_updated_at) {
            return 'no_permission'; // YouTube permission not granted
        }
        
        if (!$this->hasSubscriptions()) {
            return 'no_subscriptions'; // Not subscribed to any channel
        }
        
        return 'active'; // Everything is fine
    }
}
