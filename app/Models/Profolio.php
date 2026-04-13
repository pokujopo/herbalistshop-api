<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profolio extends Model
{
    use HasFactory;

    protected $table = 'profolios';
    protected $fillable = [
        'name','link', 'language',
    ];

    public function view(){
        return $this->hasMany(View::class, 'project_id', 'id');
    }

    public function like(){
        return $this->hasMany(Like::class, 'project_id', 'id');
    }

  
}
