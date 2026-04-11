<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner_image extends Model
{
    use HasFactory;

    protected $table = 'banner_images';
    protected $fillable = [
        'image_one','image_three', 'image_two', 'image_offer'
    ];
}
