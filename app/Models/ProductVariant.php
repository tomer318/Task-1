<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'version_name',
        'color_name',
        'color_image',
        'price',
        'stock',
        'sku'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}