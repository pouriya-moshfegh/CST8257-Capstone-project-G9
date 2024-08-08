<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'file_path', 'publish_option', 'user_id'];

    // تعریف رابطه با مدل User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function getLikeCountAttribute()
    {
        return $this->likes()->where('type', 'like')->count();
    }

    public function getDislikeCountAttribute()
    {
        return $this->likes()->where('type', 'dislike')->count();
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
