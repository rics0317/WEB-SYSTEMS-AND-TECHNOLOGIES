<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'quantity', 'variations', 'variation_option_id', 'variation_option_size_id'];

    protected $casts = [
        'variations' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variationOption()
    {
        return $this->belongsTo(VariationOption::class, 'variation_option_id');
    }

    public function variationOptionSize()
    {
        return $this->belongsTo(VariationOptionSize::class, 'variation_option_size_id');
    }
}
