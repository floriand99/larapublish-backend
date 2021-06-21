<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function articles(){
        return $this->belongsToMany(\App\Models\Article::class);
    }

    public function setContentAttribute($value){
        $this->attributes['content'] = strtolower($value);
    }
}
