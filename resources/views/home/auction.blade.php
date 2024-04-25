<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Page</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
    <script>
        // Define a variable to store the current highest bid amount
        var currentHighestBidAmount = {{ $auction->current_highest_bid }}||0;

        // Initialize Pusher
        var pusher = new Pusher('a2e0226e8071fa644e1a', {
            cluster: 'ap2'
        });

        // Subscribe to the auction channel (replace auctionId with the actual auction ID)
        var channel = pusher.subscribe('auction_18_1');

        /// Function to update the highest bid display
        function updateHighestBidDisplay(newBidAmount, userName) {
            // Format the bid amount with commas for thousands separators and two decimal places
            var formattedBidAmount = parseFloat(newBidAmount).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            // Update the text of the highest bid display
            $('#highest-bid').text("Highest Bid: $" + formattedBidAmount + " by " + userName);
        }

        // Listen for the 'newBid' event
        channel.bind('newBid', function(data) {
            if(data.bid_amount==-1){
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 10000, // 10 seconds timeout
                    showMethod: 'fadeIn', // Fade in animation
                    hideMethod: 'fadeOut', // Fade out animation
                    extendedTimeOut: 1000, // Extended timeout after hover
                    preventDuplicates: true, // Prevent duplicate notifications
                    newestOnTop: true // Display newest notification on top
                };
                toastr.error('Auction has ended.');
                return;
            }   

            // Compare the new bid amount with the current highest bid amount
            if (data.bid_amount > currentHighestBidAmount) {
                // Update the current highest bid amount
                currentHighestBidAmount = data.bid_amount;

                // Update the highest bid display
                updateHighestBidDisplay(data.bid_amount, data.userOBJ.name);
            }

            // Format the new bid amount
            var formattedBidAmount = parseFloat(data.bid_amount).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            // Append the new bid information to the bids container
            var bidInfo = "New bid by User " + data.userOBJ.name + " with ID #" + data.user_id + ": $" + formattedBidAmount;
            $('#bids-container').append('<p>' + bidInfo + '</p>');
        });

        // Initial update of the highest bid display
        // if(auction->current_highest_bid!=null){
        //     updateHighestBidDisplay({{ $auction->current_highest_bid }}, '$auction->highestBid->user->name:"N/A" }}');
        // }
    </script>
    <script>
        // Function to handle bid form submission
        $(document).ready(function() {
            $('#bid-form').submit(function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Get bid amount from the form
                var bidAmount = $('#bid_amount').val();

                // Make an AJAX request to submit the bid
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(response) {
                        // Bid successfully submitted, no need to do anything else as the new bid will be received via Pusher
                    },
                    error: function(xhr, status, error) {
                        console.error(error); // Log any errors
                    }
                });
            });
        });
    </script>
</head>
<body class="p-1">
    <h1>Auction Page</h1>
    <p>{{ $auction->auction_channel }}</p>

    <!-- Auction details -->
    <div>
        <p>Auction ID: {{ $auction->id }}</p>
        <!-- Other auction details... -->
        @if ($auction->current_highest_bid!=null&&$auction->highestBid!=null)
            <p id="highest-bid">Highest Bid: ${{ number_format($auction->current_highest_bid, 2) }} by {{ $auction->highestBid->user->name }}</p>
        @else
            <p>Highest Bid: N/A</p>
        @endif
        @if (Auth::user()->id == $auction->store->seller_id)
            <form method="GET" action="{{ route('home.closeAuction', ['id' => $auction->id]) }}">
                @csrf
                <button class="flex-c-m stext-101 cl0 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer btn btn-primary" style="padding: 100px 99vh; margin: 10px;">
                    SOLD!!
                </button>
            </form>
        @else
            
        @endif
    </div>

    <!-- Bid form -->
    <form id="bid-form" action="{{ route('home.placeBid', ['id' => $auction->id]) }}" method="POST">
        @csrf
        <label for="bid_amount">Enter your bid amount:</label>
        <input type="number" id="bid_amount" name="bid_amount" required>
        <button type="submit">Place Bid</button>
    </form>

    <!-- Bid notifications container -->
    <div id="bids-container">
        <!-- Existing bid display -->
        @foreach ($bids as $bid)
            <p>New bid by User {{ $bid->user->name }} with ID #{{ $bid->user->id }} ${{ number_format($bid->bid_amount, 2) }}</p>
            {{-- "New bid by User " + data.userOBJ.name + " with ID #" + data.user_id + ": $" + formattedBidAmount --}}
        @endforeach
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</body>
</html>
