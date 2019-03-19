<?php

namespace App\Http\Controllers;

use App\Services\Twitch;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(Twitch $twitch)
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

    public function subscribe(Request $request, Twitch $twitch)
    {
        $streamer = $twitch->getUser($request->input('streamerId'))[0];

        $twitch->subscribe($streamer);

        return redirect()->route('video', [
            'streamerId' => $streamer->id,
            'streamerName' => $streamer->display_name,
        ]);
    }

}
