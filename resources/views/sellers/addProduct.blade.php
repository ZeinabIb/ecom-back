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
    <h1 class="mt-3">Add New Store</h1>
    <form action="{{ route('sellers.submitCreateProduct', ['seller' => $seller->id, 'store' => $store->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="product_description">Description</label>
            <textarea class="form-control" id="product_description" name="product_description" required></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="form-group">
            <label for="image_url">Image</label>
            <input type="file" class="form-control" id="image_url" name="image_url" required>
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Select a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Do you want to auction this product?</label><br>
            <label>By choosing "yes" an auction event will be created, set to start 1 week from creation, you can edit details and invite people from the edit store page.</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="auction_status" id="auction_yes" value="1" required>
                <label class="form-check-label" for="auction_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="auction_status" id="auction_no" value="0">
                <label class="form-check-label" for="auction_no">No</label>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
    <!-- Include Bootstrap JS (Optional, if you need Bootstrap JavaScript features) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Function to handle changes in auction status radio buttons
        function handleAuctionStatusChange() {
            var auctionYesRadio = document.getElementById('auction_yes');
            var quantityInput = document.getElementById('quantity');

            // If "Yes" is selected, set quantity to 1 and disable the input
            if (auctionYesRadio.checked) {
                quantityInput.value = 1;
                quantityInput.disabled = true;
            } else {
                // If "No" is selected, enable the quantity input
                quantityInput.disabled = false;
            }
        }

        // Add event listeners to auction status radio buttons
        document.getElementById('auction_yes').addEventListener('change', handleAuctionStatusChange);
        document.getElementById('auction_no').addEventListener('change', handleAuctionStatusChange);

        // Call the function initially to set the initial state based on the default selected radio button
        handleAuctionStatusChange();
    </script>
</body>
</html>