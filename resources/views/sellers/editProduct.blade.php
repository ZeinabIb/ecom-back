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
        <h1>Edit Product</h1>
        <form action="{{ route('sellers.submitEditProduct', ['seller' => $seller->id, 'store' => $store->id, 'product' => $product->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
            </div>
            <div class="form-group">
                <label for="product_description">Description</label>
                <textarea class="form-control" id="product_description" name="product_description" required>{{ $product->product_description }}</textarea>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input class="form-control" id="price" name="price" required value="{{ $product->price }}"/>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input class="form-control" id="quantity" name="quantity" required value="{{ $product->quantity }}"/>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="{{$product->category->id }}">{{$product->category->name }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
    <!-- Include Bootstrap JS (Optional, if you need Bootstrap JavaScript features) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>