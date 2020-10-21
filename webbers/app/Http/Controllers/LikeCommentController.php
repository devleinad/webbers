<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeCommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'afterverified', 'aftercategoryselection']);
    }

    public function store(Comment $comment_id)
    {
        if (auth()->user()->likes()->toggle($comment_id)) {
            return response()->json(['success' => true]);
        }
    }
}