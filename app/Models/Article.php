<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tags(){
        return $this->belongsToMany(\App\Models\Tag::class);
    }

    public function user(){
        return $this->belongsTo(\App\Models\User::class);
    }

    public function likes(){
        return $this->belongsToMany(\App\Models\User::class, 'likes');
    }

    public function scopeNewest($query){
        return $query->orderBy('created_at', 'desc');
    }

    public function scopePopular($query){
        return $query->withCount('likes')->orderBy('likes_count', 'desc');
    }

    public function setTitleAttribute($value){
        $this->attributes['title'] = ucwords($value);
    }
}
