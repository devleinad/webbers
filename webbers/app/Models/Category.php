<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category_name', 'is_approved'];

    public function posts()
    {
        return $this->belongsToMany('App\Models\Post');
    }


    public static function checkCategoryExistence(iterable $categories)
    {
        foreach ($categories as $category) {
            $is_Exists = Category::where("category_name", $category)->exists();
            if (!$is_Exists) {
                self::create([
                    "category_name" => $category,
                    "is_approved" => true,
                ]);
            }
        }
    }
}