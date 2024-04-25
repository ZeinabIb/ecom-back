<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = ['auction_id', 'starting_price', 'current_highest_bid', 'auction_end_time', 'product_id', 'store_id'];

    // which product are we auctioning
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // which store does this auction belong to
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // get all the bids for the auction
    public function bids()
    {
        return $this->hasMany(Bid::class, 'auction_id');
    }

    // Define a method to get the highest bid for the auction
    public function highestBid()
    {
        return $this->hasOne(Bid::class, 'auction_id')->latest('bid_amount');
    }

    // get allllllllll the invites to this auction
    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'auction_id');
    }
}
