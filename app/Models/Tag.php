<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $hidden = ['pivot','created_at','updated_at'];

    public function posts(){
        return $this->belongsToMany(Post::class, 'post_tag');
    }
}
