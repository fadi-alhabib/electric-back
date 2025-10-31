<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['sub_category_id', 'title', 'description', 'dimensions', 'key_features', 'serial_number'];

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('order_position');
    }
}
