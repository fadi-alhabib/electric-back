<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category_name', 'description', 'image'];

    public function productLines()
    {
        return $this->hasMany(ProductLine::class);
    }
}
