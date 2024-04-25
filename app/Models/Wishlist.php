<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

   protected $fillable = ['buyer_id'];

    // which user does this wishlist belong to
    public function user()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // get all the products in this wishlist
    public function products()
    {
        return $this->belongsToMany(Product::class, 'wishlist_has_products');
    }
}
