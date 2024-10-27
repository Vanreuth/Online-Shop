<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'des',
        'price',
        'qty',
        'brand_id',
        'color',
        'user_id',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Define relationship with Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // If you have multiple images, define relationship with ProductImage
    public function images()
    {
        return $this->hasMany(ProductImg::class);
    }
}
