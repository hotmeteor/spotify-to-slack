<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use SpotifyWebAPI\Session;

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

    public function tokenHasExpired(): bool
    {
        return $this->spotify_token_expires && $this->spotify_token_expires->lt(now());
    }

    public function refreshAccessToken()
    {
        $session = app(Session::class);
        $session->refreshAccessToken($this->spotify_refresh_token);

        $this->update([
            'spotify_token' => $session->getAccessToken(),
            'spotify_token_expires' => $session->getTokenExpiration() ? Carbon::createFromTimestamp($session->getTokenExpiration()) : null,
        ]);
    }
}
