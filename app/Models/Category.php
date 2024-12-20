<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $fillable = [
         'merchant',
         'meta_title',
         'meta_keywords',
         'meta_description',
         'slug',
         'name',
         'description',
         'logos',
         'image',
         'status',
    ];
}
