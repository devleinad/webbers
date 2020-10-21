<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PhotoUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $title = 'Webbers - ' . Auth::user()->username;
        return view('auth.photo_upload', compact('title'));
    }
    public function proceed(Request $request)
    {
        if ($request->get('action') && $request->get('action') == 'to-selection') {
            $update = User::where('id', $request->get('id'))->update(['reg_steps' => 2]);
            if ($update) {
                $request->session('secret', $request->user()->secret);
                $next = '/categories/choice/select';
                return response()->json(['success' => true, 'next' => $next]);
            }
        }
    }
}