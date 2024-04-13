<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'details', 'seller_id', 'store_status'];

    // get all the categories made in that store
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // get all the products in this store
    public function products()
    {
        return $this->hasManyThrough(Product::class, Category::class);
    }

    // get all the auctions made in this store
    public function auctions()
    {
        return $this->hasManyThrough(Auction::class, Product::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

}
