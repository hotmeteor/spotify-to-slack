<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    protected $casts = [
        'spotify_token_expires' => 'datetime',
    ];

    public function tracks()
    {
        return $this->hasMany(Track::class, 'user_id');
    }

    public function routeNotificationForSlack()
    {
        return $this->slack_webhook_url;
    }
}
