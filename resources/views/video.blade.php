<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .container {
            display: flex;
        }

        .fixed {
            width: 640px;
        }

        .flex-item {
            flex-grow: 1;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="fixed">
        {{--            <iframe
                            src="https://player.twitch.tv/?channel={{$streamerName}}"
                            height="360"
                            width="640"
                            frameborder="0"
                            scrolling="no"
                            allowfullscreen="true">
                    </iframe>--}}
    </div>
    <div class="flex-item">
        {{--            <iframe frameborder="0"
                            scrolling="no"
                            id="chat_embed"
                            src="https://www.twitch.tv/embed/{{$streamerName}}/chat"
                            height="660"
                            width="500">
                    </iframe>--}}
    </div>
</div>
<div id="app">
    Events:
    <events streamer-id="{{$streamerId}}"></events>
</div>
</body>
</html>
<script src="/js/app.js"></script>
<script>

</script>
