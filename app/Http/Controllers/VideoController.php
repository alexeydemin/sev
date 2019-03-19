<?php

namespace App\Http\Controllers;


class VideoController extends Controller
{
    public function __invoke(string $streamerId, $streamerName)
    {
        return view('video', compact('streamerId', 'streamerName'));
    }
}
