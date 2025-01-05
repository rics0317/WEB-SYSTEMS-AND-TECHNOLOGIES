<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variation_option_id',
        'variation_option_size_id',
        'quantity',
        'type',
        'old_stock',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variationOption()
    {
        return $this->belongsTo(VariationOption::class);
    }

    public function variationOptionSize()
    {
        return $this->belongsTo(VariationOptionSize::class);
    }

}
