<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BrandCategory;
use App\Modles\Product;
use App\Models\OrderProduct;
use App\Models\User;

class Order extends Model
{
    protected $table='rudra_order_order';

    protected $fillable = [
        'order_no',
        'email',
        'user_id',
        'category_id',
        'brand_name',
        'order_date'
        ];

    public function brand()
    {
        return $this->belongsTo(BrandCategory::class,'category_id');
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class,'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
