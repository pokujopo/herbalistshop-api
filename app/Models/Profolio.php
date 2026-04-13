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

    // Add this scope to safely get counts
    public function scopeWithCounts($query){
        return $query->selectRaw('"profolios".*')
            ->selectRaw('(select count(*) from "likes" where "profolios"."id"::text = "likes"."project_id"::text) as "like_count"')
            ->selectRaw('(select count(*) from "views" where "profolios"."id"::text = "views"."project_id"::text) as "view_count"');
    }
}
