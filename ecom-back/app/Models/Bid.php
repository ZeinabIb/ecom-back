<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = ['bid_amount', 'buyer_id', 'auction_id'];

    // which user sent this bid
    public function user()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // which auction does this bid belong to
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}
