<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
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
        } elseif ($user->tokenHasExpired()) {
            $user->refreshAccessToken();
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
            'name' => !empty($data->getName()) ? $data->getName() : $data->getId(),
            'username' => $data->getId(),
            'avatar' => $data->getAvatar(),
            'spotify_token' => $data->token,
            'spotify_refresh_token' => $data->refreshToken,
            'spotify_token_expires' => $data->expiresIn ? now()->addSecond($data->expiresIn) : null,
        ]);
    }
}
