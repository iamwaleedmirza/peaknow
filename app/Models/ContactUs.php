<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'email_verified_at' => 'datetime:m/d/Y : H:i',
    ];
}
