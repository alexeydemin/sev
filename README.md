# SEV

## Install

`composer install && npm install && npm run prod`

`php artisan websockets:serve`

## Basics

1. `/webhook/*` endpoints are polled by Twitch server.
2. Then `StreamEventOccurred` event does broadcasting.
3. `beyondcode/laravel-websockets` package handles the server side of WebSockets acting as a replacement for Pusher.
4. Laravel Echo listens for `StreamEventOccurred` event on `/video/*` pages (see `resources/js/components/Events.vue`) and updates `messages` array.

## Demo

http://www.alexeydem.in

It seems Heroku does not allow us to use PHP with websockets server in the same app:
https://help.heroku.com/8R7OO0ZV/can-i-run-a-websockets-server-using-php-on-heroku

## Assumptions

1. Favorite streamers dropdown are filled with user's Followed Channels.
2. $leaseSeconds = 864000 (10 days) must be changed to a more appropriate value (a couple of hours or less) on production.
3. Unsubscribe functionality not implemented.

## Questions
> How would you deploy the above on AWS? (ideally a rough architecture diagram will help)

For now might be deployed to EC2 as a simple monolith app.  

> Where do you see bottlenecks in your proposed architecture and how would you approach scaling this app starting from 100 reqs/day to 900MM reqs/day over 6 months?

The bottleneck is `/webhook/followers` endpoints which may get tons of requests per second.  
In the future, I'd split it to 2 applications, the first one receives webhooks from Twitch (Amazon API Gateway) and runs Websockets
and the second one is responsible for user's subscriptions and showing pages to user (simple EC2 behind ELB or Lambda).  

