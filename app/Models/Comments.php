<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Comments extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $fillable = [
        'comment','product_id', 'product_name'
    ];
}
