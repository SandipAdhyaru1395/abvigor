<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandCategory extends Model
{
     protected $table='chivalry_brand_category';

     public $timestamps=false;

     protected $fillable=[
          'title',
          'slug',
          'short_description'
          ];
     public function products(){
          return $this->hasMany(BrandProduct::class,'category_id');
     }
}
