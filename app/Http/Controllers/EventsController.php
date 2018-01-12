<?php

namespace App\Http\Controllers;

use App\User;
use GuzzleHttp\Client;
use Rollbar\Payload\Level;
use Rollbar\Rollbar;
use SpotifyWebAPI\SpotifyWebAPI;

class EventsController extends Controller
{
    public function store()
    {
        if (request()->has('challenge')) {
            return response([
                'challenge' => request('challenge'),
            ], 200);
        }

        $event = request('event');

        if (array_get($event, 'type') === 'reaction_added' && array_get($event, 'reaction') === 'heart') {
            $this->addToPlaylist($event);
        }
    }

    protected function addToPlaylist(array $event)
    {
        $user = $this->getUser($event);

        if (!$user) {
            return;
        }

        $api = new SpotifyWebAPI();
        $api->setAccessToken($user->spotify_token);

        if (!$user->spotify_playlist_id) {
            $playlist = $api->createUserPlaylist($user->username, [
                'name' => 'Slack Playlist',
            ]);

            $user->update([
                'spotify_playlist_id' => data_get($playlist, 'id'),
            ]);
        }

        $track_id = $this->getTrackId($event, $user);

        if (!$track_id) {
            return;
        }

        $api->addUserPlaylistTracks($user->username, $user->spotify_playlist_id, [
            $track_id,
        ]);
    }

    protected function getUser(array $event): ?User
    {
        $user = User::where('slack_user_id', array_get($event, 'user'))->whereNotNull('spotify_token')->first();

        if (!$user) {
            return null;
        }

        if ($user->tokenHasExpired()) {
            $user->refreshAccessToken();
        }

        return $user;
    }

    protected function getTrackId(array $event, User $user)
    {
        $client = new Client();

        $response = $client->get('https://slack.com/api/channels.history', [
            'query' => [
                'token' => $user->slack_token,
                'channel' => data_get($event, 'item.channel'),
                'count' => 1,
                'inclusive' => true,
                'latest' => data_get($event, 'item.ts'),
            ],
        ]);

        $data = json_decode($response->getBody());

        if (!$data->ok) {
            Rollbar::log(Level::DEBUG, 'Couldn\'t fetch channel data.', $event);

            return null;
        }

        $message = head(data_get($data, 'messages'));
        $attachment = head(data_get($message, 'attachments'));
        $track_id = optional(collect($attachment->fields)->where('title', 'Track ID')->first())->value;

        if (!$track_id) {
            return null;
        }

        return $track_id;
    }
}
