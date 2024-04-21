<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h3>Users</h3>
                <ul class="list-group">
                    @foreach($users as $user)
                        <li class="list-group-item user" data-name="{{ $user->name }}" data-id="{{ $user->id }}">{{ $user->name }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="col-md-8" style="margin-top: 50px;">
                <div class="card" style="height: 500px;">
                    <div class="card-header" id="chat-title">Chat</div>
                    <div class="card-body">
                        <div class="messages"></div>
                    </div>
                    <div class="card-footer">
                        <form id="message-form">
                            <div class="input-group">
                                <input type="text" class="form-control" id="message" name="message" placeholder="Type your message">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
$(document).ready(function() {
    // Click event for user list items
    $('.user').click(function() {
        var userId = $(this).data('id');
        var userName = $(this).data('name');
        $('#chat-title').text('Chat with ' + userName);

        // Fetch messages for the selected user
        $.ajax({
            url: "/fetch-messages/" + userId,
            method: 'GET',
            success: function(messages) {
                $('.messages').empty();
                messages.forEach(function(message) {
                    $('.messages').append('<div class="message">' + message.message + '</div>');
                });
            }
        });

        // Set the active user ID for sending messages
        $('#message-form').data('user-id', userId);
    });

    // Pusher subscription and message handling
    const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        cluster: 'eu'
    });
    const channel = pusher.subscribe('public');

    channel.bind('chat', function(data) {
        $(".messages").append('<div class="right message" style="background-color: #007bff; color: white; padding: 10px; border-radius: 10px; display:inline; margin-bottom: 10px;">' + message + '</div><br><br>');
        $(document).scrollTop($(document).height());
    });

    $("#message-form").submit(function(event) {
        event.preventDefault();
        var userId = $(this).data('user-id');
        var message = $("form #message").val();

        $.ajax({
            url: "/store-message",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                user_id: userId,
                message: message
            },
            success: function(response) {
                $(".messages").append('<div class="right message" style="background-color: #007bff; color: white; padding: 10px; border-radius: 10px; display:inline; margin-bottom: 10px;">' + message + '</div><br><br>');
                $(document).scrollTop($(document).height());
                $("form #message").val('');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
</body>
</html>
