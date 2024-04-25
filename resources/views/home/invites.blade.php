<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Invites</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container">
        <h1 class="mt-3">Invites</h1>
        <div class="mt-3">
            @if(count($invites) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invites as $invite)
                            <tr>
                                <td>{{ $invite->user->name }}</td>
                                <td>{{ $invite->user->email }}</td>
                                <td>{{ $invite->status }}</td>
                                <td>
                                    @if ($invite->status=="pending")
                                        <a href="{{ route('home.acceptInvite', ['id'=>$invite->id]) }}" class="btn btn-success">Accept</a>
                                        <a href="{{ route('home.declineInvite', ['id'=>$invite->id]) }}" class="btn btn-danger">Decline</a>
                                    @else
                                        Nothing to do
                                    @endif
                                </td>
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