<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','price','stock','out_of_stock','image'];

    protected static function booted()
    {
        static::saving(function ($product) {
            $product->out_of_stock = ($product->stock == 0);
        });
    }
}
