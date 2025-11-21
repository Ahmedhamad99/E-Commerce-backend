<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Order extends Model
{
    use HasFactory;
    protected $fillable = ['order_number','user_id','address','phone','total'];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }
}
