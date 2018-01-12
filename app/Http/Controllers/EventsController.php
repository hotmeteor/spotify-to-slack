<?php

namespace App\Http\Controllers;

use App\User;
use SpotifyWebAPI\SpotifyWebAPI;

class EventsController extends Controller
{
    public function store(SpotifyWebAPI $api)
    {
        if (request()->has('challenge')) {
            return response([
                'challenge' => request('challenge'),
            ], 200);
        }

//        $user = User::where('slack_user_id', request('user_id'))->where('slack_token', request('token'))->firstOrFail();
//
//        $text = trim(request('text'));
//
//        $api->addUserPlaylistTracks('USER_ID', 'PLAYLIST_ID', [
//            'TRACK_ID',
//            'TRACK_ID',
//        ]);
    }
}
