<?php

namespace App\Events;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BidMade implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $auction;
    public $userId;
    public $userOBJ;
    public $bidAmount;

    public function __construct($auctionId, $userId, $bidAmount)
    {
        $this->auction = Auction::findOrFail($auctionId);
        $this->userId = $userId;
        $this->userOBJ = User::findOrFail($userId);
        $this->bidAmount = $bidAmount; 
    }

    public function broadcastOn()
    {
        return new Channel('auction_18_1');
    }

    public function broadcastAs()
    {
        return 'newBid';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->userId,
            'userOBJ' => $this->userOBJ,
            'bid_amount' => $this->bidAmount
        ];
    }
}
