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

    public function subscribe($streamerId)
    {
        try {
            $this->api->getWebhooksSubscriptionApi()->subscribeToStream(
                $streamerId,
                'bearer',
                'https://yourwebsite.com/path/to/callback/handler',
                864000
            );
        } catch (GuzzleException $e) {
            // handle error
        }
    }

}
