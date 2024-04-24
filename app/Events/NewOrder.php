<?php
// app/Events/NewOrder.php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrder implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct()
    {
    }

    public function broadcastOn()
    {
        return new Channel('new-order');
    }

    public function broadcastAs()
    {
        return 'my-event';
    }
}
