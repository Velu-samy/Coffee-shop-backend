<?php

// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'receiver_name',
        'mobile',
        'address',
        'city',
        'state',
        'pincode',
        'landmark',
        'delivery_instructions',
        'product_name',
        'quantity',
        'price',
        'total',
    ];
}