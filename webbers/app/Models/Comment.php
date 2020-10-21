<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'user_id', 'comment', 'upvotes', 'down_votes', 'status'];

    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function likes()
    {
        return $this->belongsToMany('App\Models\User', "comment_likes");
    }

    function presentCommentLikesCount()
    {
        return $this->likes()->count();
    }

    public function presentCommentTime()
    {
        return $this->created_at->format('d M,Y h:ia');
    }

    public static function boot()
    {
        parent::boot();
        static::deleted(function ($comment) {
            $comment->likes()->delete();
            // $comment->bounty()->delete();
        });
    }

    public function hasAccepted()
    {
        return $this->where('is_best_answer', 1)->count();
    }
}