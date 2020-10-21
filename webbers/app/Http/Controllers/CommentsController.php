<?php

namespace App\Http\Controllers;

use App\Events\AnswerAccepted;
use App\Models\Post;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'afterverified', 'aftercategoryselection']);
    }

    public function store(Request $request)
    {
        if ($request->filled('comment') && $request->filled('post_id')) {
            if (Post::find($request->post_id)) {
                $save = Comment::create([
                    'post_id' => $request->get('post_id'),
                    'user_id' => Auth::id(),
                    'comment' => $request->get('comment'),
                    'is_best_answer' => false,

                ]);
                if ($save) {
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false]);
                }
            } else {
                return response()->json(['success' => false]);
            }
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function like_unlike_comments(Request $request)
    {
        if ($request->comment_id && $request->action)
            if ($request->action == "like_comment") {
                if (Comment::find($request->comment_id)) {
                    $like = DB::table('comment_likes')->insert(['comment_id' => $request->comment_id, 'user_id' => Auth::id()]);
                    if ($like) {
                        return response()->json(['success' => true]);
                    } else {
                        return response()->json(['success' => false]);
                    }
                }
            }
    }

    public function present_like_unlike(Request $request)
    {
        if ($request->get('comment_id') && $request->get('like_action')) {
            if ($request->get('like_action') == "get_like_unlike") {
                if (Comment::find($request->get('comment_id'))) {
                    // has user already liked or not
                    $is_liked = DB::table('comment_likes')->where('comment_id', $request->get('comment_id'))->where('user_id', Auth::id())->exists();
                    if ($is_liked) {
                        return response()->json(['success' => true, 'status' => 'liked', 'comment_id' => $request->get('comment_id')]);
                    } else {
                        return response()->json(['success' => true, 'status' => 'like', 'comment_id' => $request->get('comment_id')]);
                    }
                }
            }
        }
    }
    /**
     * Accept comment as best answer
     * @param Post $post
     * @param Comment $comment
     * @return \Illuminate\Http\Response 
     */

    public function accept(Post $post, Comment $comment)
    {
        // To be sure not to accept two answers as best, first check if an answer already exists

        $alreadyAnswer = DB::table('comments')->where('post_id', $post->id)
            ->where('id', '!=', $comment->id)->where('is_best_answer', true)->exists();
        if ($alreadyAnswer) {
            $setCurrentAnswerAsFalse = DB::table('comments')->where('post_id', $post->id)
                ->where('id', '!=', $comment->id)->where('is_best_answer', true)->update(['is_best_answer' => false]);
            if ($setCurrentAnswerAsFalse) {
                $setAnswer =  DB::table('comments')->where('post_id', $post->id)
                    ->where('id', '=', $comment->id)->update(['is_best_answer' => true, 'updated_at' => Carbon::now()]);
                if ($setAnswer) {
                    event(new AnswerAccepted($post));
                    return redirect()->back();
                } else {
                    return back()->with('error', 'Action Failed. Something is not right');
                }
            }
        } else {
            $setAnswer = DB::table('comments')->where('post_id', $post->id)
                ->where('id', '=', $comment->id)->update(['is_best_answer' => true, 'updated_at' => Carbon::now()]);
            if ($setAnswer) {
                event(new AnswerAccepted($post));
                return redirect()->back();
            } else {
                return back()->with('error', 'Action Failed. Something is not right');
            }
        }
    }
}