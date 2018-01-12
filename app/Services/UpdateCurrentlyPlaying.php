<?php

namespace App\Services;

use App\Notifications\SlackCurrentlyPlaying;
use App\SpotifyTrack;
use App\User;
use Illuminate\Console\Command;
use SpotifyWebAPI\SpotifyWebAPI;

class UpdateCurrentlyPlaying extends Command
{
    protected $signature = 'spotifyslack:check';

    protected $description = 'Check for the currently playing tracks.';

    public function handle(SpotifyWebAPI $api)
    {
        foreach (User::whereNotNull('spotify_token')->get() as $user) {
            if ($user->tokenHasExpired()) {
                $user->refreshAccessToken();
            }

            $this->updateCurrentlyPlaying($api, $user);
        }
    }

    protected function updateCurrentlyPlaying(SpotifyWebAPI $api, User $user)
    {
        if (!$user->spotify_token || !$new_track = $this->fetchCurrentlyPlaying($api, $user)) {
            return null;
        }

        $last_track = $this->getLastTrack($user);

        if ($this->shouldUpdate($new_track, $last_track)) {
            $track = $user->tracks()->create([
                'track_id' => $new_track->getId(),
                'title' => $new_track->getTrackName(),
                'artist' => $new_track->getArtist()->name,
                'album' => $new_track->getAlbum()->name,
                'url' => $new_track->getUrl(),
                'image' => optional(array_first($new_track->getAlbum()->images))->url,
                'duration_ms' => $new_track->getDurationMs(),
            ]);

            $user->notify(new SlackCurrentlyPlaying($track));
        }
    }

    protected function shouldUpdate($new_track, $last_track): bool
    {
        if (!$new_track->getIsPlaying()) {
            return false;
        }

        return !$last_track || $last_track->track_id !== $new_track->getId();
    }

    protected function fetchCurrentlyPlaying(SpotifyWebAPI $api, User $user): ?SpotifyTrack
    {
        $api->setAccessToken($user->spotify_token);

        if (!$new_track = $api->getMyCurrentTrack()) {
            return null;
        }

        return new SpotifyTrack($new_track);
    }

    protected function getLastTrack(User $user)
    {
        return $user->tracks()->latest()->first();
    }
}
