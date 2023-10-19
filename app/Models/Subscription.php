<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
   protected $fillable = [
       'user_id',
       'first_name',
       'last_name',
       'phone',
       'email',
       'zipcode',
       'agreement'
   ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
