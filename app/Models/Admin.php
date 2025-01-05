<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age',
        'gender',
        'civil_status',
        'birthdate',
        'contact',
        'term_start',
        'term_end',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
