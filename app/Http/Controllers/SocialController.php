<?php

namespace App\Http\Controllers;

use App\Services\Twitch;
use Illuminate\Support\Facades\Log;
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

    public function webhook()
    {
        Log::info('== GET ==' );
        Log::info(print_r($_GET, 1));
        Log::info('== END GET ==' );

        Log::info('== POST ==' );
        Log::info(print_r($_POST, 1));
        Log::info('== END POST ==' );


        echo $_GET['hub_challenge'];
    }
}
