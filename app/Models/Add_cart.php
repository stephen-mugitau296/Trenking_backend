<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Add_cart extends Model
{
    use HasFactory;

    protected $table = 'add_cart';
    protected $fillable = [
         'user_id',
         'product_id',
         'product_id',
    ];
     
    protected $with = ['product'];
    public function product(){

        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
