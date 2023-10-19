<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BelugaOrderDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'email_verified_at' => 'datetime:m/d/Y : H:i',
    ];

    public function order()
    {
       return $this->hasOne(Order::class,'order_no','order_no');
    }
}
