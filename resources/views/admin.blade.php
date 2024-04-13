<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .clickable-row {
            cursor: pointer;
        }

        .clickable-row:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Admin Dashboard</a>
</nav>

<div class="container">
    <h1 class="mt-3">Stores</h1>

    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stores as $store)
                <tr class="clickable-row" data-id="{{ $store->id }}">
                    <td>{{ $store->id }}</td>
                    <td>{{ $store->name }}</td>
                    <td>{{ $store->details }}</td>
                    <td>
                        @if ($store->store_status == 'Active')
                            <span class="badge badge-success">{{ $store->store_status }}</span>
                        @else
                            <span class="badge badge-warning">{{ $store->store_status }}</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.toggleStoreStatus', $store->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-primary">
                                @if ($store->store_status == 'Active')
                                    Deactivate
                                @else
                                    Activate
                                @endif
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Users
                </div>
                <div class="card-body">
                    @if (session('password_reset_success'))
                        <div class="alert alert-success">{{ session('password_reset_success') }}</div>
                    @endif
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Reset Password</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <form action="{{ route('admin.resetUserPassword', $user->id) }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control" placeholder="New Password" required>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-outline-primary">Reset Password</button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".clickable-row").click(function() {
            var storeId = $(this).data("id");
            window.location = "/admin/store/" + storeId + "/edit";
        });
    });
</script>

</body>
</html>
