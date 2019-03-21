<?php

namespace App\Http\Controllers;

use App\Events\FollowingOccurred;
use App\Services\Twitch;
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

        file_put_contents(storage_path('mylog.log'), "== GET ==\n", FILE_APPEND);
        file_put_contents(storage_path('mylog.log'), print_r($_GET, 1) . "\n", FILE_APPEND);
        file_put_contents(storage_path('mylog.log'), "== END GET ==\n", FILE_APPEND);

        file_put_contents(storage_path('mylog.log'), date('d.m.y H:i:s') . "[]== POST ==\n", FILE_APPEND);
        file_put_contents(storage_path('mylog.log'), print_r($_POST, 1) . "\n", FILE_APPEND);
        file_put_contents(storage_path('mylog.log'), file_get_contents('php://input') . "\n", FILE_APPEND);
        file_put_contents(storage_path('mylog.log'), "== END POST ==\n", FILE_APPEND);


        if(isset($_GET['hub_challenge'])) {
            die($_GET['hub_challenge']);
        }

        broadcast(new FollowingOccurred('zemelya follows Ninja'));
    }
}
