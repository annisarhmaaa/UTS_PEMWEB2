<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    // Menambahkan kolom yang boleh diisi massal
    protected $fillable = [
        'name', 'slug', 'sku', 'description', 'price', 'stock', 'product_category_id', // Menyesuaikan dengan kolom yang ada
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'product_category_id');
    }

}