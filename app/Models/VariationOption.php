<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariationOption extends Model
{
    protected $fillable = [
        'variation_id',
        'name',
        'stock'
    ];

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    public function sizes()
    {
        return $this->hasMany(VariationOptionSize::class, 'option_id');
    }
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}