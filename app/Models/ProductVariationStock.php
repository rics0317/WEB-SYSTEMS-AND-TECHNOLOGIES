<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariationStock extends Model
{
    use HasFactory;

    protected $fillable = ['product_variation_id', 'option', 'stock'];

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
}
