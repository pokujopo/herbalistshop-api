<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class View extends Model
{
    use HasFactory;

    protected $table = 'views';
    protected $fillable = [
        'view', 'project_id'
    ];

    public function project(){
        return $this->belongsTo(Profolio::class);
    }
}
