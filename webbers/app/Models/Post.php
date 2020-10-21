<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_title',
        'post_body',
        'identifier',
        'slug',
        'post_category',
        'post_status',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function categories()
    {
        return $this->hasMany("App\Models\Tag");
    }

    public function bounty()
    {
        return $this->hasOne('App\Models\Bounty');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }


    public function presentPostStatus()
    {
        switch (true) {
            case $this->post_status == "unanswered":
                return "<span class='badge badge-pill badge-primary float-right text-white'>unanswered</span>";
                break;
            case $this->post_status == "answered":
                return "<span class='badge badge-pill badge-success text-right'>answered</span>";
                break;
        }
    }

    public function presentDateTime()
    {
        if ($this->created_at == $this->updated_at) {
            return 'posted on ' . $this->created_at->format('d M, Y | h:isa');
        } else {
            return 'edited on ' . $this->updated_at->format('d M, Y | h:isa');
        }
    }

    public function presentTrimmedPostBody()
    {
        return $this->post_body;
    }

    public function presentPostTag()
    {
        return ucfirst($this->post_tag);
    }


    public function presentBounty()
    {
        echo '<span class="badge badge-warning">+' . $this->bounty->bounty_points . ' points</span>';
    }

    public function presentPostViews()
    {
        return DB::table('post_views')->where('post_id', $this->id)->count();
    }

    public static function getWithIdentifier($identifier)
    {
        return self::where('identifier', $identifier)->firstOrFail();
    }

    function deleteLikesForCommentsUnderPost()
    {
        $comment_id = $this->comments()->pluck('id')->all();
        DB::table('comment_likes')->whereIn('comment_id', $comment_id)->delete();
    }

    public static function boot()
    {
        parent::boot();
        static::deleted(function ($post) {
            $post->comments()->delete();
            $post->bounty()->delete();
        });
    }

    public function hasAcceptedAnswer()
    {
        if ($this->comments()->where('is_best_answer', 1)->exists()) {
            return true;
        }
    }
}