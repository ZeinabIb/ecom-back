<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'product_description', 'price', 'quantity', 'image_url', 'auction_status', 'auction_end_time', 'store_id', 'category_id'];

    // get the store to which this product was enlisted on
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // get the category of this product
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // get all the orders that have this product in them
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'orders_has_products', 'product_id', 'order_id')
                    ->withPivot('quantity');
    }
}
