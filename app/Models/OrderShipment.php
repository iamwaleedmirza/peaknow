<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShipment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getOrderRefill()
    {
        return OrderRefill::where('order_no', $this->order_no)->where('refill_number', $this->refill_number)->first();
    }
}
