<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRefill extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function refillShipment()
    {
        return OrderShipment::where('order_no', $this->order_no)->where('refill_number', $this->refill_number)->first();
    }

    public function order()
    {
       return $this->hasOne(Order::class,'order_no','order_no');
    }
}
