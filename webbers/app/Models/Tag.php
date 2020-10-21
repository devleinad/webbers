<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['tag_name', 'is_approved'];

    public function posts()
    {
        return $this->belongsToMany('App\Models\Post');
    }

    public static function checkTagExistence(iterable $tags)
    {
        foreach ($tags as $tag) {
            $is_Exists = Tag::where("tag_name", $tag)->exists();
            if (!$is_Exists) {
                self::create([
                    "tag_name" => $tag,
                    "is_approved" => true,
                ]);
            }
        }
    }
}