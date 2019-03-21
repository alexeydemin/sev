<?php

namespace App\Overridden;

use GuzzleHttp\Client;
use NewTwitchApi\HelixGuzzleClient;

class WebhooksCustomSubscriptionApi
{
    public const SUBSCRIBE = 'subscribe';
    public const UNSUBSCRIBE = 'unsubscribe';

    private $clientId;
    private $secret;
    private $guzzleClient;

    public function __construct(string $clientId, string $secret, Client $guzzleClient = null)
    {
        $this->clientId = $clientId;
        $this->secret = $secret;
        $this->guzzleClient = $guzzleClient ?? new HelixGuzzleClient($clientId);
    }

    public function subscribeToFollows(string $streamerId): void
    {
        $this->toggleSubscription(
            sprintf('https://api.twitch.tv/helix/users/follows?first=1&to_id=%s', $streamerId),
            'http://941485fe.ngrok.io/webhook/followers' /*route('wh.followers')*/
        );
    }

    public function subscribeToStreamChanges(string $streamerId): void
    {
        $this->toggleSubscription(
            sprintf('https://api.twitch.tv/helix/streams?user_id=%s', $streamerId),
            'http://941485fe.ngrok.io/webhook/stream'/*route('wh.stream')*/
        );
    }

    public function subscribeToUserChanges(string $streamerId): void
    {
        $this->toggleSubscription(
            sprintf('https://api.twitch.tv/helix/users?id=%s', $streamerId),
            'http://941485fe.ngrok.io/webhook/user'/*route('wh.user')*/
        );
    }

    public function unsubscribeFromFollows(string $streamerId): void
    {
        $this->toggleSubscription(sprintf('https://api.twitch.tv/helix/users/follows?first=1&to_id=%s', $streamerId), false);
    }

    public function validateWebhookEventCallback(string $xHubSignature, string $content): bool
    {
        [$hashAlgorithm, $expectedHash] = explode('=', $xHubSignature);
        $generatedHash = hash_hmac($hashAlgorithm, $content, $this->secret);

        return $expectedHash === $generatedHash;
    }

    private function toggleSubscription(
        string $topic,
        string $callback,
        bool $enable = true,
        int $leaseSeconds = 864000
    ): void
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Client-ID' => $this->clientId,
        ];

        $body = [
            'hub.callback' => $callback,
            'hub.mode' => $enable ? self::SUBSCRIBE : self::UNSUBSCRIBE,
            'hub.topic' => $topic,
            'hub.lease_seconds' => $leaseSeconds,
            'hub.secret' => $this->secret,
        ];

        $this->guzzleClient->post('webhooks/hub', [
            'headers' => $headers,
            'body' => json_encode($body),
        ]);
    }

}