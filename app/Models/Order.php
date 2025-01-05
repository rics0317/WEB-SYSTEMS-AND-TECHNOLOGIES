<?php

// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'region',
        'province',
        'city',
        'barangay',
        'postal_code',
        'street_address',
        'label',
        'total_price',
        'products',
        'payment_id',
        'payment_status',
        'order_status',
        'payment_method', // Add payment_method to the fillable array
    ];

    protected $casts = [
        'products' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderHistories()
    {
        return $this->hasMany(OrderHistories::class);
    }
    
}
