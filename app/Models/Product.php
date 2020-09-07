<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'SKU', 'name', 'stock', 'price', 'description', 'img', 'user_id'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
