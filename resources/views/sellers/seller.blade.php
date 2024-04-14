<!-- resources/views/sellers/seller.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Information</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
    <!-- Include Bootstrap JS (Optional, if you need Bootstrap JavaScript features) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
