<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OauthController;
use App\Http\Controllers\LikeCommentController;
use App\Http\Controllers\UserPostTypeController;
use App\Http\Controllers\Auth\PhotoUploadController;
use PhpParser\Parser\Tokens;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/tuts/image_upload', function () {
    return view('tuts.image_upload');
});

Route::get('/tuts', function (Request $request) {
    return Http::asForm()->post('http://webbers.com/login', [
        'email' => 'peprahdaniel.dp@gmail.com',
        'password' => '123456',
        '_token' => Session::csrf_token(),
    ]);
});

Route::get('/storage/test', function () {
    //if (Storage::disk('public')->put('file.txt', 'Contents')) dd("Done!")}
    //make a new directory in the public directory
    //$new_dir = Storage::makeDirectory('/public/news_directory');
    Storage::disk('public')->deleteDirectory('news_directory');
    $files = Storage::allDirectories('public');
    dd($files);
});

Auth::routes(['verify' => true]);

Route::get('/home', [PostController::class, 'index'])->name('home');
Route::get('/posts/deliver', [PostController::class, 'deliverPosts'])->name('deliver_posts');
Route::get('/questions/ask', [PostController::class, 'create'])->name('questions.create');
Route::post('/questions/store', [PostController::class, 'store'])->name('questions.store');
Route::get('/questions/{identifier}/edit', [PostController::class, 'edit'])->name('questions.edit');
Route::patch('/questions/{identifier}', [PostController::class, 'update'])->name('questions.update');
Route::get('/user/photo_upload', [PhotoUploadController::class, 'index'])->name('photo_upload');
Route::post('/questions/hide_unhide', [PostController::class, 'hide_unhide'])->name('questions.hide_unhide');
Route::delete('/user/{post}', [PostController::class, 'destroy'])->name('questions.destroy');
Route::patch('/user/photo_upload/proceed', [PhotoUploadController::class, 'proceed'])->name('photo_upload_proceed');
Route::get('/categories/choice/select', [UserPostTypeController::class, 'index'])->name('user_category_choice');
Route::any('/user/choice', [UserPostTypeController::class, 'user_category_choice'])->name('user_choice');
Route::delete('/user_choice', [UserPostTypeController::class, 'destroy'])->name('user_choice_destroy');
Route::post('/comments', [CommentsController::class, 'store'])->name('comments.store');
Route::patch('/question/{post}/comment/{comment}', [CommentsController::class, 'accept'])->name('comments_accept');
Route::post('/like/{comment_id}', [LikeCommentController::class, 'store'])->name('like_');
Route::get('/like/get_count', [LikeCommentController::class, 'get_count']);
Route::get('/oauth/{provider}', [OauthController::class, 'handleProviderToRedirect'])->name('oauth')->where('provider', 'google|github|twitter');
Route::get('/oauth/{provider}/callback', [OauthController::class, 'handleRedirectedProvider'])->where('provider', 'google|github|twitter');
Route::get('/questions/{identifier}/', [PostController::class, 'show'])->name('posts.show');