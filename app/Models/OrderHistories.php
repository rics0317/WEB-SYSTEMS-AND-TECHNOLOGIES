<?php

// app/Models/OrderHistories.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistories extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'field_name',
        'old_value',
        'new_value',
    ];

    protected $hidden = [
        'old_value',
        'new_value',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
