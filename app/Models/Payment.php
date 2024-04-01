<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['payment_method', 'transaction_id', 'order_id'];

    // which order does this payment belong to
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
