<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __invoke(string $streamer)
    {
        return view('video', compact('streamer'));
    }
}
