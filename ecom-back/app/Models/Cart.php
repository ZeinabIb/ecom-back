<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_id'];

    // which user does this cart belong to
    public function user()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // get all the products in this cart
    public function products()
    {
        return $this->belongsToMany(Product::class, 'carts_has_products', 'cart_id', 'product_id');
    }
}
