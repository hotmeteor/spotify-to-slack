<?php

return [

    'spotify' => [
        'client_id' => env('SPOTIFY_KEY'),
        'client_secret' => env('SPOTIFY_SECRET'),
        'redirect' => env('SPOTIFY_REDIRECT_URI'),
    ],

    'slack' => [
        'client_id' => env('SLACK_CLIENT_ID'),
        'client_secret' => env('SLACK_CLIENT_SECRET'),
        'redirect' => env('SLACK_REDIRECT_URI'),
    ],

];
