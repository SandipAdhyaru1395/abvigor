<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BrandCategory;
use Illuminate\Support\Facades\Storage;

class CatalogProduct extends Model
{
    protected $table = 'chivalry_catalog_product';

    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'product_code',
        'technical_specification',
    ];

    public function Brand()
    {
        return $this->belongsTo(BrandCategory::class, 'category_id');
    }

    public function Image()
    {
        return $this->morphOne(\App\Models\SystemFile::class, 'attachment')
            ->where('field', 'product_image');

    }

    public function getImageUrlAttribute()
    {
        
        $image= SystemFile::where('attachment_id', $this->id)
        ->where('attachment_type', 'Chivalry\Catalog\Models\Product')
        ->where('field', 'product_image')->first();
       
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
