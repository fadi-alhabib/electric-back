<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLine extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'image'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
