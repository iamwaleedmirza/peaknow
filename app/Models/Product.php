<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuids;

class Product extends Model
{
    use HasFactory,HasUuids;

    protected $casts = [
        'created_at' => 'datetime:m/d/Y : H:i',
        'updated_at' => 'datetime:m/d/Y : H:i',
    ];
}
