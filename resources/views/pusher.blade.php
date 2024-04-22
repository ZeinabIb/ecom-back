<!DOCTYPE html>
<html>
<head>
    <title>Pusher Test</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Additional styling */
        .message-bubble {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
            background-color: #f0f0f0;
        }

        .sender {
            color: blue;
            font-weight: bold;
        }

        .receiver {
            color: green;
            font-weight: bold;
        }
    </style>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('023a642915c846c21251', {
            cluster: 'ap2'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            var messageContainer = document.getElementById('messages');
            var messageHTML = '<div class="message-bubble">' +
                                    '<span class="' + data.type + '">' + data.sender + ': </span>' +
                                    data.message +
                                '</div>';
            messageContainer.innerHTML += messageHTML;
        });

        // Function to send message to the server
        function sendMessage() {
            var messageInput = document.getElementById('messageInput').value;

            fetch('/sendPusher', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: messageInput })
            })
            .then(response => response.json())
            .then(data => console.log(data))
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Pusher Test</h1>
        <div id="messages" class="mt-3"></div>
        <div class="input-group mt-3">
            <input type="text" id="messageInput" class="form-control" placeholder="Enter your message">
            <div class="input-group-append">
                <button onclick="sendMessage()" class="btn btn-primary">Send</button>
            </div>
        </div>
    </div>
</body>
</html>
