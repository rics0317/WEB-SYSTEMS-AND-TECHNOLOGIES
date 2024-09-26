<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'description',
        'price',
        'stock',
        'image', // Add image to fillable
        
    ];



    // Define the belongs-to relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Define the belongs-to relationship with Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Calculate the discounted price of the product.
     *
     * @param float $discountPercentage
     * @return float
     */
    public function getDiscountedPrice(float $discountPercentage): float
    {
        // Ensure the discount percentage is between 0 and 100
        $discountPercentage = min(max($discountPercentage, 0), 100);
        $discountAmount = ($this->price * $discountPercentage) / 100;
        return $this->price - $discountAmount;
    }

    /**
     * Check if the product is in stock.
     *
     * @param int $quantity
     * @return bool
     */
    public function isInStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }
}