<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BrandProduct;

class OrderProduct extends Model
{
    protected $table = "rudra_order_products";

     protected $fillable=[
        'order_id',
        'product_id',
        'order_number',
        'user_email',
        'qty',
        'category_title',
        'product_name'
    ];
    
    public function product()
    {
        return $this->belongsTo(BrandProduct::class,'product_id');
    }
}
