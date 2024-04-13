<!DOCTYPE html>
<html>
<head>
    <title>Edit Store</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="row">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Store Information
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.updateStore', $store->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $store->name }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="details">Details</label>
                            <textarea class="form-control" id="details" name="details" disabled>{{ $store->details }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="motif">Motif</label>
                            <input type="text" class="form-control" id="motif" name="motif" value="{{ $store->motif }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="store_status">Status</label>
                            <select class="form-control" id="store_status" name="store_status" disabled>
                                <option value="Active" {{ $store->store_status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ $store->store_status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <input type="hidden" name="seller_id" value="{{ $store->seller_id }}">

                    </form>


                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Seller Information
                </div>
                <div class="card-body">
        @if ($seller)
            <form>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ $seller->username }}" disabled>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $seller->email }}" disabled>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $seller->phone }}" disabled>
                </div>
            </form>
            <!-- Password reset form -->
            <a href="#" id="resetPasswordLink" class="text-danger">Reset Password</a>
            <form id="resetPasswordForm" style="display: none;" action="{{ route('sellers.resetPassword', $seller->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="old_password">Old Password</label>
                    <input type="password" class="form-control" id="old_password" name="old_password">
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
            <div id="passwordUpdateMessage" style="display: none;"></div>
        @else
            <p>No seller information found.</p>
        @endif
        @if (session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @elseif (session('error'))
                        <div class="alert alert-danger mt-3">
                            {{ session('error') }}
                        </div>
                    @endif
    </div>

            </div>
        </div>
    </div>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.getElementById('resetPasswordLink').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('resetPasswordForm').style.display = 'block';
    });
</script>
</body>
</html>
