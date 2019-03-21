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

    public function subscribeToFollows(string $twitchId, string $callback, int $leaseSeconds = 0): void
    {
        $this->toggleSubscription(
            true,
            sprintf('https://api.twitch.tv/helix/users/follows?first=1&to_id=%s', $twitchId),
            $callback,
            $leaseSeconds
        );
    }

    public function unsubscribeFromFollows(string $twitchId, string $callback, int $leaseSeconds = 0): void
    {
        $this->toggleSubscription(
            false,
            sprintf('https://api.twitch.tv/helix/users/follows?first=1&to_id=%s', $twitchId),
            $callback,
            $leaseSeconds
        );
    }

    public function validateWebhookEventCallback(string $xHubSignature, string $content): bool
    {
        [$hashAlgorithm, $expectedHash] = explode('=', $xHubSignature);
        $generatedHash = hash_hmac($hashAlgorithm, $content, $this->secret);

        return $expectedHash === $generatedHash;
    }

    private function toggleSubscription(bool $enable, string $topic, string $callback, int $leaseSeconds = 0): void
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