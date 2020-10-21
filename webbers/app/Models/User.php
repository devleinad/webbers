<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'user_type',
        'identifier',
        'reg_steps',
        'password',
        'user_state',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }

    public function categories()
    {
        return $this->hasMany('App\Models\Category');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post', 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function likes()
    {
        return $this->belongsToMany("App\Models\Comment", "comment_likes");
    }


    public function reputation()
    {
        return $this->hasOne('App\Models\Reputation');
    }

    public function social_accounts()
    {
        return $this->hasMany('App\Models\SocialAccount');
    }

    public function setting()
    {
        return $this->hasOne('App\Models\Setting');
    }

    public function getVerifiedTag()
    {
        if ($this->hasVerifiedEmail()) {
            echo '<img src="' . asset("storage/icons/verified.png") . '" width="20" class="mb-1">';
        }
    }

    public function setReputation()
    {
        if ($this->hasVerifiedEmail()) {
            $this->reputation()->create([
                'reputation_points' => 0,
                'status' => 'approved',
            ]);
        }
    }

    public function presentReputation()
    {
        $reputation_point = $this->reputation->reputation_points;
        switch (true) {
            case $reputation_point < 50:
                echo '<span class="fa fa-square text-info" style="font-size:7px"></span>';
                break;

            case $reputation_point > 50 && $reputation_point < 80:
                echo '<span class="fa fa-square text-warning" style="font-size:7px"></span>';
                break;

            case $reputation_point >= 80:
                echo '<span class="fa fa-square text-success" style="font-size:7px"></span>';
                break;
        }
    }



    public static function boot()
    {
        parent::boot();
        static::created(function ($user) {
            $user->setting()->create([
                'enable-notifications' => true,
                'ushured' => false,
                'ok_with_categories' => false,
            ]);
        });
    }
}