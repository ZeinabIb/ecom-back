<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite Buyer</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-3">Invite By Email</h1>
    <form action="{{ route('sellers.submitInviteBuyerToAuction', ['seller' => $seller->id, 'store' => $store->id, 'auction' => $auction->id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
        </div>
        <button type="submit" class="btn btn-primary">Invite</button>
    </form>
</div>
    <!-- Include Bootstrap JS (Optional, if you need Bootstrap JavaScript features) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>