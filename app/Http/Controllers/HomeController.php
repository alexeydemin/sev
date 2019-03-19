<?php

namespace App\Http\Controllers;

use App\Services\Twitch;
use Illuminate\Support\Facades\Request;

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
                ->pluck('to_id', 'to_name')
                ->toArray();
        }

        return view('index', [
            'isAuthorized' => (bool) $userId,
            'streamers' => $streamers
        ]);
    }

    public function subscribe(Request $request, Twitch $twitch)
    {
        $streamerId = $request->input('streamerId');
        $twitch->subscribe($streamerId);

        return redirect()->route('video', ['streamerId' => $streamerId]);
    }

}
