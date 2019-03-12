<?php

namespace App\Http\Controllers;

use App\Services\Twitch;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __invoke(Twitch $twitch)
    {
        $streamers = [];
        $userId = session('userId');

        if ($userId) {
            echo 'userId=' . session('userId');
            $streamers = $twitch
                ->getFollows($userId)
                ->pluck('to_name', 'to_id')
                ->toArray();
        }

        return view('index', [
            'isAuthorized' => (bool) $userId,
            'streamers' => $streamers
        ]);
    }

}
