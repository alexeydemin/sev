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
        } catch (GuzzleException $e) {
            // handle error
        }

        $responseContent = json_decode($response->getBody()->getContents());

        return collect($responseContent->data);
    }

}
