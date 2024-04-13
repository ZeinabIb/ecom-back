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
                <form>
                @if ($seller)
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
                @else
                    <p>No seller information found.</p>
                @endif

            </form>
        </div>

            </div>
        </div>
    </div>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
