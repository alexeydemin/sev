<?php

namespace App\Services;

use NewTwitchApi\HelixGuzzleClient;
use NewTwitchApi\NewTwitchApi;
use GuzzleHttp\Exception\GuzzleException;

class Twitch
{

    protected $api;

    public function __construct()
    {
        $helixGuzzleClient = new HelixGuzzleClient(config('services.twitch.client_id'));

        $this->api = new NewTwitchApi(
            $helixGuzzleClient,
            config('services.twitch.client_id'),
            config('services.twitch.client_secret')
        );
    }

    public function getFollows($userId)
    {
        try {
            $response = $this->api->getUsersApi()->getUsersFollows($userId);
            $responseContent = json_decode($response->getBody()->getContents());

            return collect($responseContent->data);
        } catch (GuzzleException $e) {
            // handle error
            return false;
        }


    }

    public function getUser($userId)
    {
        try {
            $response = $this->api->getUsersApi()->getUserById($userId);
            $responseContent = json_decode($response->getBody()->getContents());

            return $responseContent->data;
        } catch (GuzzleException $e) {
            // handle error
            return false;
        }
    }

    public function subscribe($streamer)
    {
        try {
            $this->api->getWebhooksSubscriptionApi()->subscribeToStream(
                $streamer->id,
                'bearer',
                "http://alexeydemin-sev.herokuapp.com/video/{$streamer->id}/{$streamer->display_name}",
                864000
            );
        } catch (GuzzleException $e) {
            // handle error
        }
    }

}
