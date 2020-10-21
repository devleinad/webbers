<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Bounty;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Reputation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AskQuestionRequest;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $title = 'Webbers - ' . Auth::user()->username;
        $get_user_selected_categories = DB::table('user_categories')
            ->where('user_id', Auth::user()->id)
            ->pluck('category_id')
            ->all();

        $get_seleted_categories_names = DB::table('categories')
            ->whereIn('id', $get_user_selected_categories)
            ->pluck('category_name')->all();

        $user_hidden_posts = DB::table('user_posts_hidden')
            ->where('user_id', Auth::id())
            ->pluck('post_id')
            ->all();

        $posts = '';

        if ($request->query('tag')) {
            $posts = Post::where('post_category', $request->query('tag'))
                ->whereNotIn('id', $user_hidden_posts)
                ->orderBy('created_at', 'DESC')->simplePaginate(10);
        } else if ($request->query('filter_by')) {
            if ($request->query('filter_by') == "unanswered") {
                $posts = Post::whereIn('post_category', $get_seleted_categories_names)
                    ->where('post_status', 'unanswered')
                    ->whereNotIn('id', $user_hidden_posts)
                    ->orderBy('created_at', 'DESC')->simplePaginate(10);
            } else if ($request->query('filter_by') == "answered") {
                $posts = Post::whereIn('post_category', $get_seleted_categories_names)
                    ->where('post_status', 'answered')
                    ->whereNotIn('id', $user_hidden_posts)
                    ->orderBy('created_at', 'DESC')->simplePaginate(10);
            } else if ($request->query('filter_by') == 'bountied') {

                //posts that have been bountied:return post_id
                $bountiedIds = DB::table('bounties')
                    ->whereIn('post_id', $get_user_selected_categories)
                    ->whereNotIn('id', $user_hidden_posts)
                    ->pluck('post_id')->all();


                $posts = Post::whereIn('id', $bountiedIds)
                    ->whereNotIn('id', $user_hidden_posts)
                    ->orderBy('created_at', 'DESC')
                    ->simplePaginate(10);
            } else if ($request->query('filter_by') == 'hidden') {
                $posts = Post::whereIn('id', $user_hidden_posts)
                    ->orderBy('created_at', 'DESC')
                    ->simplePaginate(10);
            }
        } else {
            $posts = Post::whereIn('post_category', $get_seleted_categories_names)
                ->whereNotIn('id', $user_hidden_posts)
                ->orWhere('user_id', Auth::user()->id)
                ->whereNotIn('id', $user_hidden_posts)
                ->orderBy('created_at', 'DESC')
                ->simplePaginate(10);
        }

        //dd($comments);

        return view('posts.index', compact('title', 'posts'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Webbers - Ask Question';
        return view('posts.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $exploded_tags = explode(',', $request->post_tag);

        if (isExists($request->post_title) > 0) {
            return back()->with("error", "Duplicate! It appears this question has been asked before.")->withInput();
        }

        if (!blockedUser(Auth::user()->id)) {

            if (count($exploded_tags) > 5) {
                return back()->with("error_tag", "Sorry, you cannot add more than 5 tags")->withInput();
            }

            $post_identifier = rand(100000, 999999);

            $post = Auth::user()->posts()->create([
                "post_title" => $request->post_title,
                "post_body" => $request->post_content,
                "identifier" => $post_identifier,
                "slug" => encodeSlug($request->post_title),
                "post_category" => $request->post_tag,
                "post_status" => "unanswered",
            ]);

            if ($request->filled('bounty')) {
                if (!is_numeric($request->bounty)) {
                    return back()->with('error_bounty', 'The value you have provided as bounty is invalid. It must be a number')->withInput();
                } else if (getReputaionPoints(Auth::user()->id) < $request->bounty) {
                    return back()->with('error_bounty', 'Sorry, you do not have enough point to place this bounty')->withInput();
                }

                $post->bounty()->create([
                    "post_id" => $post->id,
                    "bounty_points" => $request->bounty,
                    "status" => "active",
                ]);

                $posterReputationPoints = Reputation::where('user_id', $post->user_id)->value('reputation_points');
                Reputation::where('user_id', $post->user_id)->update(['reputation_points' => $posterReputationPoints - $request->bounty]);
            }

            if ($post) {
                Category::checkCategoryExistence($exploded_tags);
                return redirect()->route("home");
            } else {
                return back()->with('error', 'Action failed. Something went wrong');
            }
        } else {
            return redirect()->route('questions')->with('error', 'Sorry, you have been blocked from making a post!');
        }
    }



    public function show($identifier)
    {
        $title = "Webbers - " . Auth::user()->username;
        $post = Post::where('identifier', $identifier)->firstOrFail();

        $isViewedAlready = DB::table('post_views')->where('post_id', $post->id)
            ->where('user_id', Auth::id())->exists();
        if (false == $isViewedAlready && $post->user_id != Auth::id()) {
            DB::table('post_views')->insert(['post_id' => $post->id, 'user_id' => Auth::id()]);
        }
        $comments = Comment::where('post_id', $post->id)->orderBy('id', 'ASC')->get();


        return view('posts.show', compact('post', 'title', 'comments'));
    }



    /**
     *show a particuar resource.
     *
     * @param  string  $identifier
     * @return \Illuminate\Http\Response
     */

    public function edit($identifier)
    {
        //$post = Post::where('identifier', $identifier)->firstOrFail();
        if (false == blockedUser(auth()->user()->id)) {
            $title = 'Webbers - Edit Post';
            $post = Post::getWithIdentifier($identifier);
            $this->authorize('update', $post);
            return view('posts.edit', compact('title', 'post'));
        } else {
            return back()->with('access_denied', 'Oops! It appears you do not have permission for this action');
        }
    }

    public function update(AskQuestionRequest $request, $identifier)
    {
        $post = Post::getWithIdentifier($identifier);
        if (false == blockedUser(Auth::id())) {
            $exploded_tags = explode(',', $request->post_tag);
            $validator = Validator::make($request->all(), $request->rules());
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                if (false == Post::where('post_title', $request->post_title)->where('identifier', '!=', $identifier)
                    ->exists()
                ) {
                    $update = $post->update([
                        'post_title' => $request->post_title,
                        'post_body' => $request->post_content,
                        'slug' => encodeSlug($request->post_title),
                        'post_category' => $request->post_tag,
                        'updated_at' => Carbon::now(),
                    ]);

                    if ($request->filled('bounty')) {
                        if (!is_numeric($request->bounty)) {
                            return back()->with('error_bounty', 'The value you have provided as bounty is invalid. It must be a number')->withInput();
                        } else if (getReputaionPoints($update->user_id) < $request->bounty) {
                            return back()->with('error_bounty', 'Sorry, you do not have enough point to place this bounty')->withInput();
                        }

                        $update->bounty()->create([
                            "post_id" => $update->id,
                            "bounty_points" => $request->bounty,
                            "status" => "active",
                        ]);

                        $posterReputationPoints = Reputation::where('user_id', $update->user_id)->value('reputation_points');
                        Reputation::where('user_id', $update->user_id)->update(['reputation_points' => $posterReputationPoints - $request->bounty]);
                    }

                    if ($update) {
                        Category::checkCategoryExistence($exploded_tags);
                        return redirect()->route("home")->with('success', 'Update was successful!');
                    } else {
                        return back()->with('error', 'Action failed. Something went wrong');
                    }
                } else {
                    return back()->with('error', 'Sorry, this question has already been asked!');
                }
            }
        } else {
            return back()->with('access_denied', 'Oops! It appears you do not have permission to perform this action');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->deleteLikesForCommentsUnderPost();
        $delete = $post->delete();
        if ($delete) {
            return redirect()->route('home')->with('success', 'Post deleted successfully');
        } else {
            return back()->with('error', 'Post could not be deleted. Something went wrong');
        }
    }

    /**
     * Hide the specified resource from storage.
     *
     * @param  int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function hide_unhide(Request $request)
    {
        if ($request->action == "hide") {
            if (Post::find($request->id)) {
                $hide = DB::table('user_posts_hidden')
                    ->insert([
                        'post_id' => $request->id,
                        'user_id' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'created_at' => Carbon::now()
                    ]);
                if ($hide) {
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false]);
                }
            } else {
                return response()->json(['success' => false]);
            }
        }
    }
}