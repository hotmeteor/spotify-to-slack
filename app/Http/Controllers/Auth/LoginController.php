<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use SpotifyWebAPI\Session;

class LoginController extends Controller
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function redirectToProvider()
    {
        return Socialite::driver('spotify')
            ->scopes([
                'user-read-currently-playing',
            ])
            ->redirect();
    }

    public function handleProviderCallback()
    {
        $data = Socialite::driver('spotify')->stateless()->user();

        if (!$user = $this->getUser($data)) {
            $user = $this->createUser($data);
        } elseif ($user->spotify_token_expires && $user->spotify_token_expires->lt(now())) {
            $this->refreshAccessToken($user, $data->refreshToken);
        }

        Auth::login($user);

        return redirect('/');
    }

    protected function getUser($data)
    {
        return User::where('username', trim($data->getId()))->first();
    }

    protected function createUser($data): User
    {
        return User::create([
            'name' => $data->getName(),
            'username' => $data->getId(),
            'avatar' => $data->getAvatar(),
            'spotify_token' => $data->token,
            'spotify_refresh_token' => $data->refreshToken,
            'spotify_token_expires' => $data->expiresIn ? now()->addSecond($data->expiresIn) : null,
        ]);
    }

    protected function refreshAccessToken(User $user, $refresh_token)
    {
        $this->session->refreshAccessToken($refresh_token);

        $user->update([
            'spotify_token' => $this->session->getAccessToken(),
            'spotify_token_expires' => $this->session->getTokenExpiration() ? Carbon::createFromTimestamp($this->session->getTokenExpiration()) : null,
        ]);
    }
}
