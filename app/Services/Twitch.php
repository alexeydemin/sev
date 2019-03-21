<?php

namespace App\Services;

use App\Overridden\WebhooksCustomSubscriptionApi;
use NewTwitchApi\HelixGuzzleClient;
use NewTwitchApi\NewTwitchApi;
use GuzzleHttp\Exception\GuzzleException;

class Twitch
{

    protected $api;
    protected $customApi;

    public function __construct()
    {
        $helixGuzzleClient = new HelixGuzzleClient(config('services.twitch.client_id'));

        $this->api = new NewTwitchApi(
            $helixGuzzleClient,
            config('services.twitch.client_id'),
            config('services.twitch.client_secret')
        );
        $this->customApi = new WebhooksCustomSubscriptionApi(
            config('services.twitch.client_id'),
            config('services.twitch.client_secret'),
            new HelixGuzzleClient(config('services.twitch.client_id'))
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
        $this->customApi->subscribeToFollows($streamer->id);
        $this->customApi->subscribeToStreamChanges($streamer->id);
        $this->customApi->subscribeToUserChanges($streamer->id);
    }
    public function unsubscribe($streamer)
    {
        try {
            $this->customApi->unsubscribeFromFollows(
                $streamer->id,
                route('wh')
            );
        } catch (GuzzleException $e) {
            // handle error
        }
    }

}
