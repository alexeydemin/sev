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
    //connect();

    const host = location.origin.replace(/^http/, 'ws')
    console.log('HOST=' + host);
    let ws = new WebSocket(host)
  });
</script>
