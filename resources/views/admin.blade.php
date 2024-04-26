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
        .resetPasswordFields {
            display: none;
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

    <h1 class="mt-3">Users</h1>

    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{$user->usertype}}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger resetPasswordButton" data-user-id="{{ $user->id }}">Reset Password</button>
                        <div class="resetPasswordFields" id="resetPasswordForm_{{ $user->id }}">
                            <form action="{{ route('admin.resetUserPassword', $user->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="old_password_{{ $user->id }}">Old Password:</label>
                                    <input type="password" name="old_password" id="old_password_{{ $user->id }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_password_{{ $user->id }}">New Password:</label>
                                    <input type="password" name="new_password" id="new_password_{{ $user->id }}" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif
    </div>

    <h1 class="mt-3">Pending Stores</h1>

<div class="table-responsive mt-3">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Details</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($pendingStores as $store)

            <tr>
                <td>{{ $store->id }}</td>
                <td>{{ $store->name }}</td>
                <td>{{ $store->details }}</td>
                <td>
                    <form action="{{ route('admin.acceptStore', $store->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Accept</button>
                    </form>
                    <form action="{{ route('admin.rejectStore', $store->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<link rel="stylesheet" href="https://www.gstatic.com/dialogflow-console/fast/df-messenger/prod/v1/themes/df-messenger-default.css">
<script src="https://www.gstatic.com/dialogflow-console/fast/df-messenger/prod/v1/df-messenger.js"></script>
<df-messenger
  location="us-west1"
  project-id="fresh-booster-420114"
  agent-id="39067796-12b3-403e-ab1d-469418d12d0d"
  language-code="en"
  max-query-length="-1">
  <df-messenger-chat-bubble
   chat-title="">
  </df-messenger-chat-bubble>
</df-messenger>
<style>
  df-messenger {
    z-index: 999;
    position: fixed;
    --df-messenger-font-color: #000;
    --df-messenger-font-family: Google Sans;
    --df-messenger-chat-background: #f3f6fc;
    --df-messenger-message-user-background: #d3e3fd;
    --df-messenger-message-bot-background: #fff;
    bottom: 16px;
    right: 16px;
  }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".clickable-row").click(function() {
            var storeId = $(this).data("id");
            window.location = "/admin/store/" + storeId + "/edit";
        });

        $(".resetPasswordFields").hide();

        $(".resetPasswordButton").click(function() {
            var userId = $(this).data("user-id");
            $(".resetPasswordFields").hide();
            $("#resetPasswordForm_" + userId).show();
        });
    });
</script>

</body>
</html>
