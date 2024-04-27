<!-- resources/views/sellers/seller.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Information</title>

    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    {{-- <script>
        toastr.options = {
            closeButton: true, // Show close button
            progressBar: true, // Show progress bar
            positionClass: 'toast-bottom-right', // Position notifications at the bottom-right corner
        };
    </script> --}} 
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
    <script>
  
      var pusher = new Pusher('a2e0226e8071fa644e1a', {
        cluster: 'ap2'
      });
  
      var channel = pusher.subscribe('new-order');
      channel.bind('my-event', function(data) {
        // toastr.success('New order just arrived.', { timeOut: 5000 });
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
        toastr.success('New order just arrived.');
        setTimeout(() => {
            // Reload the current page
            location.reload();
        }, 3000);
      });
    </script>
</head>
<body>
    <div class="container">
        <h1 class="mt-3">Seller Information</h1>
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Username: {{ $seller->username }}</h5>
                <p class="card-text">Email: {{ $seller->email }}</p>
                <p class="card-text">Phone: {{ $seller->phone }}</p>
                <!-- Add more fields for seller information here -->
            </div>
        </div>
    </div>
    <div class="container">
        <h1 class="mt-3">Seller Stores</h1>
        <div class="mt-3">
            <a href="{{ route('sellers.createStore', ['seller' => $seller->id]) }}" class="btn btn-primary">Add Store</a>
        </div>
        <div class="mt-3">
            @if(count($stores) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stores as $store)
                            <tr>
                                <td>{{ $store->name }}</td>
                                <td>{{ $store->details }}</td>
                                <td>{{ $store->store_status }}</td>
                                <td>
                                    <a href="{{ route('sellers.editStore', ['seller' => $seller->id, 'store' => $store->id]) }}" class="btn btn-primary">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No stores found.</p>
            @endif
        </div>
    </div>

    <div class="container">
        <h1 class="mt-3">Seller Orders</h1>
        <div class="mt-3">
            @if(count($orders) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Store Name</th>
                            <th>Total</th>
                            <th>Payement Status</th>
                            <th>Order Status</th>
                            <th>Buyer Name</th>
                            <th>Buyer Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->products[0]->store->name }}</td>
                                <td>$ {{ number_format($order->total_amount, 2) }}</td>
                                <td>{{ $order->payment_status }}</td>
                                <td>{{ $order->order_status }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->user->email }}</td>
                                <td>
                                    <a href="{{ route('sellers.viewOrder', ['id' => $order->id]) }}" class="btn btn-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No Orders found.</p>
            @endif
        </div>
    </div>
    <!-- Include Bootstrap JS (Optional, if you need Bootstrap JavaScript features) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

</body>
</html>