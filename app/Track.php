<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Track extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    protected $casts = [
        'duration_ms' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
