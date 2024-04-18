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
        <h1>Edit Store</h1>
        <form action="{{ route('sellers.submitEditStore', ['seller' => $seller->id, 'store' => $store->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $store->name }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required>{{ $store->details }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Store</button>
        </form>
    </div>

    <div class="container">
        <h1 class="mt-3">Categories</h1>
        <div class="mt-3">
            <a href="{{ route('sellers.createCategory', ['seller' => $seller->id, 'store' => $store->id]) }}" class="btn btn-primary">Add Category</a>
        </div>
        <div class="mt-3">
            @if(count($categories) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <a href="{{ route('sellers.deleteCategory', ['seller' => $seller->id, 'store' => $store->id, 'category' => $category->id]) }}" class="btn btn-primary">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No Categories found.</p>
            @endif
        </div>
    </div>

    <div class="container">
        <h1 class="mt-3">Products</h1>
        <div class="mt-3">
            <a href="{{ route('sellers.createProduct', ['seller' => $seller->id, 'store' => $store->id]) }}" class="btn btn-primary">Add Product</a>
        </div>
        <div class="mt-3">
            @if(count($products) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Auction Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->product_description }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td><img src="/products/{{ $product->image_url }}" alt="product_image" width="150px"/></td>
                                <td>{{ $product->auction_status==0?"No":"Yes" }}</td>
                                <td>
                                    <a href="{{ route('sellers.deleteProduct', ['seller' => $seller->id, 'store' => $store->id, 'product' => $product->id]) }}" class="btn btn-primary">Delete</a>
                                    <a href="{{ route('sellers.editProduct', ['seller' => $seller->id, 'store' => $store->id, 'product' => $product->id]) }}" class="btn btn-primary">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No Products found.</p>
            @endif
        </div>
    </div>

    <div class="container">
        <h1 class="mt-3">Auctions</h1>
        <div class="mt-3">
            @if(count($auctions) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Starting Price</th>
                            <th>Current Highest Bid</th>
                            <th>Auction Start Time</th>
                            <th>Auction End Time</th>
                            <th>Product Name</th>
                            <th>Store Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($auctions as $auction)
                            <tr>
                                <td>{{ $auction->id }}</td>
                                <td>{{ $auction->starting_price }}</td>
                                <td>{{ $auction->current_highest_bid?$auction->current_highest_bid:'N/A' }}</td>
                                <td>{{ $auction->auction_start_time?$auction->auction_start_time:'N/A' }}</td>
                                <td>{{ $auction->auction_end_time?$auction->auction_end_time:'N/A' }}</td>
                                <td>{{ $auction->product->name }}</td>
                                <td>{{ $auction->store->name }}</td>
                                <td style="display: flex; gap: 8px">
                                    <form action="{{ route('sellers.deleteAuction', ['seller' => $seller->id, 'store' => $store->id, 'auction' => $auction->id]) }}" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this auction? It will also delete the product.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-primary" onclick="event.stopPropagation();">Delete</button>
                                    </form>
                                    <a href="" class="btn btn-primary" onclick="event.stopPropagation();">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No Auctions found. To get an auction started you need to add a product with "auction" option enabled</p>
            @endif
        </div>
    </div>
    <!-- Include Bootstrap JS (Optional, if you need Bootstrap JavaScript features) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>