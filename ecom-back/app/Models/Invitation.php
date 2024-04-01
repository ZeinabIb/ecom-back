<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = ['invitation_id', 'status', 'auction_id', 'buyer_id'];

    // which auction are we inviting to
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    // which user is being invited
    public function user()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
