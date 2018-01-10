<?php


namespace App\Http\Controllers;

use App\SpotifyTrack;
use Illuminate\Support\Facades\Auth;
use SpotifyWebAPI\SpotifyWebAPI;

class HomeController extends Controller
{
    private $api;

    public function __construct(SpotifyWebAPI $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        return view('home')
            ->with('current_track', $this->getCurrentTrack());
    }

    protected function getCurrentTrack()
    {
        if (!Auth::check() || !Auth::user()->spotify_token) {
            return;
        }

        $this->api->setAccessToken(Auth::user()->spotify_token);

        $track = $this->api->getMyCurrentTrack();

        if (!$track) {
            return;
        }

        $track = new SpotifyTrack($track);

        return (object) [
            'album' => $track->getAlbum(),
            'artist' => $track->getArtist(),
            'name' => $track->getTrackName(),
            'url' => $track->getUrl(),
            'is_playing' => $track->getIsPlaying(),
        ];
    }
}
