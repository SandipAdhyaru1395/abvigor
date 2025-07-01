<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemFile extends Model
{
    protected $fillable = [
        'disk_name',
        'file_name',
        'file_size',
        'content_type',
        'field',
        'attachment_type',
        'attachment_id',
        'is_public',
        'sort_order',
    ];

}
