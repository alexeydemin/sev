<?php

namespace App\Http\Controllers;

use App\Events\FollowingOccurred;
use App\Services\Twitch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function webhook(Request $r, Twitch $twitch)
    {
        $userId = $r->input('off');
        if ($userId) {
            $streamer = $twitch->getUser($userId)[0];
            $twitch->unsubscribe($streamer);
        }

        file_put_contents(storage_path('mylog.log'), date('d.m.y H:i:s') . ' ', FILE_APPEND);
        file_put_contents(storage_path('mylog.log'), file_get_contents('php://input') . "\n", FILE_APPEND);

        $json = json_decode(file_get_contents('php://input'), true);
        $follower = $json['data'][0]['from_name'];
        $streamer = $json['data'][0]['to_name'];
        $streamerId = $json['data'][0]['to_id'];
        $at = Carbon::parse($json['data'][0]['followed_at'])->format('m/d/Y H:i:s');
        $msg = "$follower followed $streamer at $at";

        if(isset($_GET['hub_challenge'])) {
            die($_GET['hub_challenge']);
        }

        broadcast(new FollowingOccurred($streamerId, $msg));
    }
}
