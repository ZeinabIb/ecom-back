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
        <h1>Edit Auction</h1>
        <form action="{{ route('sellers.updateAuction', ['seller' => $seller->id, 'store' => $store->id, 'auction' => $auction->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="auction_start_time">Auction Start Time:</label>
                <input type="datetime-local" id="auction_start_time" name="auction_start_time" class="form-control" value="{{ $auction->auction_start_time }}" required>
            </div>
    
            <div class="form-group">
                <label for="auction_end_time">Auction End Time:</label>
                <input type="datetime-local" id="auction_end_time" name="auction_end_time" class="form-control" value="{{ $auction->auction_end_time }}" required>
            </div>
    
            <button type="submit" class="btn btn-primary">Update Auction</button>
        </form>
    </div>

    <div class="container">
        <h1 class="mt-3">Invites</h1>
        <div class="mt-3">
            <a href="{{ route('sellers.inviteBuyerToAuction', ['seller' => $seller->id, 'store' => $store->id, 'auction' => $auction->id]) }}" class="btn btn-primary">Invite a buyer</a>
        </div>
        <div class="mt-3">
            @if(count($auction->invitations) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($auction->invitations as $invite)
                            <tr>
                                <td>{{ $invite->user->name }}</td>
                                <td>{{ $invite->user->email }}</td>
                                <td>{{ $invite->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No Invites found.</p>
            @endif
        </div>
    </div>
    <!-- Include Bootstrap JS (Optional, if you need Bootstrap JavaScript features) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>