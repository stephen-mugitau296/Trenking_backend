<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
    "category_id",
    "category_slug",
    "merchant",
    "slug",
    "name",
    "description",
    "title",
    "keywords",
    "meta_description",
    "selling_price",
    "original_price",
    "qty",
    "brand",
    "image",
    "featured",
    "popular",
    "status",
    ];
    
    protected $with = ['category'];
    public function category(){
        return $this->belongsTo(Category::class, 'category_id','id');
    }
}
