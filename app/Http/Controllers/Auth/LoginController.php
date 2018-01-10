<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        $user = Socialite::driver('spotify')->stateless()->user();

        dd($user);
    }
}
