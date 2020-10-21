<?php

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Bounty;
use App\Models\Profile;
use App\Models\Reputation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


if (!function_exists("getPosterName")) {
    function getPosterName($id)
    {
        return DB::table("users")->where("id", "=", $id)->value("username");
    }
}

if (!function_exists('getAvatar')) {
    function getAvatar($id)
    {
        $avatar = Profile::where('user_id', $id)->value('avatar');
        if (preg_match("/https:/", $avatar)) {
            return $avatar;
        }
        return asset('storage/' . $avatar);
    }
}


if (!function_exists('getTrimmedContent')) {
    function getTrimmedContent($identifier, $content)
    {
        $exploded_with_div_delimiter = explode('</p>', $content);
        if (count($exploded_with_div_delimiter) > 0 && strlen($exploded_with_div_delimiter[0]) > 200) {
            return substr($exploded_with_div_delimiter[0], 0, 190) . '<a href="/questions/' . $identifier . '"> read more</a>';
        } else {
            return $exploded_with_div_delimiter[0];
        }
    }
}

function getTimeAction($created_at, $updated_at)
{
    if ($created_at == $updated_at) {
        echo "asked on " . $updated_at;
    } else {
        echo "edited on " . $updated_at;
    }
}

function encodeSlug($slug)
{
    $slug = str_replace(" ", "-", $slug);
    $slug = str_replace("%", "%25", $slug);
    $slug = str_replace('(', '-', $slug);
    $slug = str_replace(')', '-', $slug);
    return $slug;
}

function getViewsCount($id)
{
    return Post::where("post_identifier", $id)
        ->value("views");
}

function blockedUser($id)
{
    return DB::table("blocked_users")->where("user_id", $id)->exists();
}

function socialUser($id)
{
    return DB::table('users')->leftJoin('social_accounts', 'users.id', 'social_accounts.id')
        ->where('users.id', '=', $id)
        ->where('social_accounts.user_id', '=', $id)
        ->exists();
}

function getReputaionPoints($user_id)
{
    return Reputation::where('user_id', $user_id)->where('status', 'approved')->value('reputation_points');
}

function presentReputation($id)
{
    $reputation_point = getReputaionPoints($id);
    switch (true) {
        case $reputation_point < 50:
            return '<span class="fa fa-square text-danger" style="font-size:11px"></span>';
            break;

        case $reputation_point > 50 && $reputation_point < 80:
            return  '<span class="fa fa-square text-warning" style="font-size:11px"></span>';
            break;

        case $reputation_point > 80:
            return  '<span class="fa fa-square text-success" style="font-size:11px"></span>';
            break;
    }
}

function isBountied($post_id)
{
    return Bounty::where('post_id', $post_id)->where('status', 'active')->exists();
}


function isExists($title)
{
    $is_exists = Post::where("post_title", "LIKE", '%' . $title . '%')->where("post_status", "active")->exists();
    return $is_exists;
}

function color()
{
    $list_of_colors = [
        '#78E7DB',
        '#F1948A',
        '#85929E',
        '#E7E778',
        '#F32FDF',
        '#4698b4',
        '#1B2631',
        '#58D68D',
        '#2E86C1',
        '#8E44AD',
        '#ebe9ee',
    ];
    $picked_color = array_rand($list_of_colors, 1);
    return $list_of_colors[$picked_color];
}

function getUserCategories($id)
{
    $user_categories = DB::table('user_categories')->where('user_id', $id)->pluck('category_id')->all();
    return DB::table('categories')->whereIn('id', $user_categories)->select('*')->inRandomOrder()->take(12)->get();
}

function getUserCategoriesCount($id)
{
    return DB::table('user_categories')->where('user_id', $id)->count();
}

function getDifferentCategories($id)
{
    $user_categories = DB::table('user_categories')->where('user_id', $id)->pluck('category_id')->all();
    return DB::table('categories')->whereNotIn('id', $user_categories)->select('*')->take(12)->get();
}

function getName($table, $resource_id, $value)
{
    return DB::table($table)->where('id', $resource_id)->value($value);
}

function getUserRegisterationSteps($id)
{
    return User::where('id', $id)->value('reg_steps');
}

function presentPostCommentsCount($post_id)
{
    return DB::table('comments')->where('post_id', $post_id)->count();
}

function isHidden($post_id)
{
    return DB::table('user_posts_hidden')->where('post_id', $post_id)->exists();
}