<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';
    protected $fillable = [

        'firstname',
        'lastname',
        'email',
        'number',
        'address',
        'city',
        'state',
        'zip',
        'payment_id',
        'payment_mode',
        'tracking_no',
        'status',
        'remarks',
    ];

    public function orderitem(){

        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
