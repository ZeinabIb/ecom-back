<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="chat">

<div class="top">
    <img/>
    <h4>Elie</h4>
    <small>Online</small>
</div>
<div class="messages">
    @include('receive', ['message'=>"Hello, this is a message"])
</div>
<div class="bottom">
    <form>
        <input type="text" id="message" name="message" placeholder="Enter message.." autocomplete="off">
        <button type="submit"></button>
    </form>
</div>
</div>

</body>


<script>
    const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        cluster: 'eu'
    });
    const channel = pusher.subscribe('public');

    channel.bind('chat', function(data) {
        console.log(data);
        $(".messages").append('<div class="left message"><p>receiver</p><p>' + data.message + '</p></div>');
        $(document).scrollTop($(document).height());
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: "/broadcast",
            method: 'POST',
            headers: {
                'X-Socket-Id': pusher.connection.socket_id
            },
            data: {
                _token: '{{ csrf_token() }}',
                message: $("form #message").val()
            },
        }).done(function(res) {
            console.log(res);
            $(".messages").append('<div class="right message"><p>' + $("form #message").val() + '</p><p>broadcast</p></div>');
            $("form #message").val('');
            $(document).scrollTop($(document).height());
        });
    });
</script>

</html>
