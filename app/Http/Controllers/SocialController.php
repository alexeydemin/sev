<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {

        $userSocial = Socialite::driver($provider)->user();

        session([
            'userId' => $userSocial->user['id']
        ]);

        return redirect()->route('home');
    }
}
