<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserPostTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'afterverified',]);
    }

    public function index()
    {
        $title = 'Webbers -' . auth()->user()->username;

        return view('auth.user_post_types', compact('title'));
    }

    public function user_category_choice(Request $request)
    {

        if ($request->get('action') && $request->get('action') == 'fetch_different_categories') {
            $categories = getDifferentCategories($request->get('user_id'));
            $output = '';
            foreach ($categories as $row) {
                $output .= '<div class="col-lg-4 col-lg-3 text-center p-2">
                <button class="btn btn-md btn-white category" data-id="' . $row->id . '" style="border:1px solid ' . color() . ';border-radius:50px">' . $row->category_name . '</button>
                </div>';
            }

            return response()->json([
                'success' => true,
                'categories_count' => $categories->count(),
                'data' => $output,
            ]);
        }

        if ($request->get('category_id')) {
            if ($request->get('action') == 'selected') {
                $save = DB::table('user_categories')->insert([
                    'user_id' => Auth::user()->id,
                    'category_id' => $request->get('category_id'),
                ]);

                if ($save) {
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false]);
                }
            }
        }


        if ($request->get('user_id') && $request->get('action') == 'get_user_categories_count') {
            return response()->json(['count' => getUserCategoriesCount($request->get('user_id')), 'success' => true]);
        }

        if ($request->get('user_id') && $request->get('action') == 'reset') {
            $deleteAll = DB::table('user_categories')->where('user_id', $request->get('user_id'))->delete();
            if ($deleteAll) {
                return response()->json(['success' => true]);
            }
        }

        if ($request->get('action') && $request->get('action') == 'finalise') {
            $finalise = User::where('id', $request->get('user_id'))->update(['reg_steps' => 3]);
            if ($finalise) {
                return response()->json(['success' => true]);
            }
        }

        if ($request->get('action') && $request->get('action') == 'get_selected_categories') {
            $selected_categories = getUserCategories(Auth::user()->id);
            $out_put = '';
            if ($selected_categories->count() > 0) {
                $out_put .= ' <h6>Followed Categories</h6>';

                foreach ($selected_categories as $selected) {
                    $out_put .= '<a href="' . route("home", ["tag" => $selected->category_name]) . '" class="category-item"><i class="fa fa-circle" style="color:' . color() . '"></i> ' . ucfirst($selected->category_name) . ' <span class="fa fa-ellipsis-h float-right mt-1" style="display:none"></span></a> ';
                }
            }

            return response()->json([
                'success' => true,
                'data' => $out_put,
            ]);
        }

        if ($request->get('action') && $request->get('action') == "ok_with_categories") {
            $update = Setting::where('user_id', Auth::user()->id)->update(['ok_with_categories' => true]);
            if ($update) {
                return response()->json(['success' => true]);
            }
        }

        if ($request->get('action') && $request->get('action') == "close-alert-ushur") {
            $update = Setting::where('user_id', Auth::user()->id)->update(['ushured' => true]);
            if ($update) {
                return response()->json(['success' => true]);
            }
        }
    }

    public function destroy(Request $request)
    {
        if ($request->get('category_id') && $request->get('action') == 'unselected') {

            $delete = DB::table('user_categories')->where('user_id', Auth::user()->id)
                ->where('category_id', $request->get('category_id'))
                ->delete();

            if ($delete) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }
}