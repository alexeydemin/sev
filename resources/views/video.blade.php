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
        <iframe
                src="https://player.twitch.tv/?channel={{$streamerName}}"
                height="360"
                width="640"
                frameborder="0"
                scrolling="no"
                allowfullscreen="true">
        </iframe>
        <div id="app">
            Last 10 events:
            <events streamer-id="{{$streamerId}}"></events>
        </div>
    </div>
    <div class="flex-item">
        <iframe frameborder="0"
                scrolling="no"
                id="chat_embed"
                src="https://www.twitch.tv/embed/{{$streamerName}}/chat"
                height="660"
                width="500">
        </iframe>
    </div>
</div>
</body>
</html>
<script src="/js/app.js"></script>
<script>

</script>
