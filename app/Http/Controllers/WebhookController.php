<?php

namespace App\Http\Controllers;

use App\Events\StreamEventOccurred;
use Carbon\Carbon;

class WebhookController extends Controller
{
    public $json;

    public function __construct()
    {
        if(isset($_GET['hub_challenge'])) {
            die($_GET['hub_challenge']);
        }

        file_put_contents(storage_path('mylog.log'), date('d.m.y H:i:s') . ' ', FILE_APPEND);
        file_put_contents(storage_path('mylog.log'), file_get_contents('php://input') . "\n", FILE_APPEND);

        $this->json = json_decode(file_get_contents('php://input'), true);
    }

    public function webhookFollowers()
    {
        $streamerId = $this->json['data'][0]['to_id'];
        $follower = $this->json['data'][0]['from_name'];
        $streamer = $this->json['data'][0]['to_name'];
        $at = Carbon::parse($this->json['data'][0]['followed_at'])->format('m/d/Y H:i:s');
        $msg = "$follower followed $streamer at $at";

        broadcast(new StreamEventOccurred($streamerId, $msg));
    }

    public function webhookStreamChanges()
    {
        $streamerId = 0;
        if(! $this->json['data'][0]){
            $msg = 'Stream went offline';
        } else {
            $streamerId = $this->json['data'][0]['to_id'];
            $userName = $this->json['data'][0]['user_name'];
            $title = $this->json['data'][0]['title'];
            $viewerCount = $this->json['data'][0]['viewer_count'];
            $language = $this->json['data'][0]['language'];

            $msg = "Stream changed: $userName | $title | $viewerCount viewers | $language";
        }

        broadcast(new StreamEventOccurred($streamerId, $msg));
    }

    public function webhookUserChanges()
    {
        $streamerId = $this->json['data'][0]['id'];
        $userName = $this->json['data'][0]['display_name'];
        $login = $this->json['data'][0]['login'];
        $description = $this->json['data'][0]['description'];
        $viewerCount = $this->json['data'][0]['viewer_count'];
        $type = $this->json['data'][0]['type'];

        $msg = "Stream changed: $userName | $login | $viewerCount viewers | $description | $type";


        broadcast(new StreamEventOccurred($streamerId, $msg));
    }
}
