<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/4.3/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('ac63e4ba64a71a9f00a9', {
      cluster: 'ap1',
      forceTLS: true
    });

    var channel = pusher.subscribe('record_added');
    channel.bind('App\\Events\\NodeRecordEvent', function(data) {
      console.log(JSON.stringify(data));
    });


    var channel2 = pusher.subscribe('test_channel');
    channel2.bind('my_event', function(data) {
      alert(JSON.stringify(data));
    });
  </script>
</head>
<body>


</body>
