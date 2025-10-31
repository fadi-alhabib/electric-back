<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['product_line_id', 'name'];

    public function productLine()
    {
        return $this->belongsTo(ProductLine::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
