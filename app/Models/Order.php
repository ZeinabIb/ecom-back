<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['total_amount', 'payment_status', 'order_status', 'buyer_id'];

    // which user does this order belong to
    public function user()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // get all the products in this order
    public function products()
    {
        return $this->belongsToMany(Product::class, 'orders_has_products', 'order_id', 'product_id')
                    ->withPivot('quantity');
    }
}
