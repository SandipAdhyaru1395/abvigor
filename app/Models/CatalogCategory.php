<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogCategory extends Model
{
     protected $table = 'chivalry_catalog_category';

     public $timestamps = false;

     protected $fillable = [
          'title',
          'slug',
          'short_description'
     ];
     public function products()
     {
          return $this->hasMany(CatalogProduct::class, 'category_id');
     }

     public function children()
     {
          return $this->hasMany(CatalogCategory::class, 'parent_id', 'id');
     }

     public function getImageAttribute()
     {
          return SystemFile::where('attachment_id', $this->id)
               ->where('attachment_type', 'Chivalry\Catalog\Models\Category')
               ->where('field', 'image')->first();;

     }

      public function getBannerAttribute()
     {
          return SystemFile::where('attachment_id', $this->id)
               ->where('attachment_type', 'Chivalry\Catalog\Models\Category')
               ->where('field', 'banner_image')->first();;

     }

     public function getImageUrlAttribute()
     {

          $image = SystemFile::where('attachment_id', $this->id)
               ->where('attachment_type', 'Chivalry\Catalog\Models\Category')
               ->where('field', 'image')->first();

          if (!$image) {
               return null;
          }

          $path = substr($image->disk_name, 0, 3) . '/' .
               substr($image->disk_name, 3, 3) . '/' .
               substr($image->disk_name, 6, 3) . '/' .
               $image->disk_name;


          return asset('/storage/app/uploads/public/' . $path);
     }

     public function getBannerUrlAttribute()
     {

          $image = SystemFile::where('attachment_id', $this->id)
               ->where('attachment_type', 'Chivalry\Catalog\Models\Category')
               ->where('field', 'banner_image')->first();

          if (!$image) {
               return null;
          }

          $path = substr($image->disk_name, 0, 3) . '/' .
               substr($image->disk_name, 3, 3) . '/' .
               substr($image->disk_name, 6, 3) . '/' .
               $image->disk_name;


          return asset('/storage/app/uploads/public/' . $path);
     }
}
