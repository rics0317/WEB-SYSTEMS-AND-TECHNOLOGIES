<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationOptionSize extends Model
{
    use HasFactory;

    protected $fillable = ['option_id', 'name', 'stock'];

    public function option()
    {
        return $this->belongsTo(VariationOption::class, 'option_id');
    }
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

}
