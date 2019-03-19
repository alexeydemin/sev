<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
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

            .m-b-md {
                margin-bottom: 30px;
            }

            .container{
                display: flex;
            }
            .fixed{
                width: 640px;
            }
            .flex-item{
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
    <div>
        Events:
    </div>
    </body>
</html>
<script src="/js/app.js"></script>
<script>
  function heartbeat() {
    let message = {type: 'PING'};
    //$('.ws-output').append('SENT: ' + JSON.stringify(message) + '\n');
    console.log('SENT: ' + JSON.stringify(message));
    ws.send(JSON.stringify(message));
  }


  function connect() {
    console.log('start connect function')         ;
    let heartbeatInterval = 1000 * 6; //ms between PING's
    let reconnectInterval = 1000 * 3; //ms to wait before reconnect
    let heartbeatHandle;

    ws = new WebSocket('wss://pubsub-edge.twitch.tv');

    ws.onopen = function(event) {
      $('.ws-output').append('INFO: Socket Opened\n');
      heartbeat();
      heartbeatHandle = setInterval(heartbeat, heartbeatInterval);
    };

    ws.onerror = function(error) {
      //$('.ws-output').append('ERR:  ' + JSON.stringify(error) + '\n');
      console.log('ERR:  ' + JSON.stringify(error));
    };

    this.ws.addEventListener('message', message => {
        console.log('msg received' + JSON.stringify(message));
    });

    ws.onmessage = function(event) {
      let message = JSON.parse(event.data);
      //$('.ws-output').append('RECV: ' + JSON.stringify(message) + '\n');
      console.log('RECV: ' + JSON.stringify(message));
      if (message.type === 'RECONNECT') {
        //$('.ws-output').append('INFO: Reconnecting...\n');
        console.log('INFO: Reconnecting...\n');
        setTimeout(connect, reconnectInterval);
      }
    };

    ws.onclose = function() {
      //$('.ws-output').append('INFO: Socket Closed\n');
      console.log('INFO: Socket Closed\n');
      clearInterval(heartbeatHandle);
      console.log('INFO: Reconnecting...\n');
      setTimeout(connect, reconnectInterval);
    };

  }

  $(function() {
    connect();
  });
</script>
