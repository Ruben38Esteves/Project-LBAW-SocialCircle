<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LikeController extends Controller
{

    public function like($postid)
    {
        if(!Auth::check() || Auth::user()->cannot('create', Like::class)){
            return redirect()->back();
        }
        $like = new Like();
        $like->postid = $postid;
        $like->userid = Auth::id();
        $like->save();
        return redirect()->back();
    }

    public function dislike($postid)
    {
        $like = Like::where('postid',$postid)->first();
        if(!Auth::check() || Auth::user()->cannot('delete', $like)){
            return redirect()->back();
        }
        $like->delete();
        return redirect()->back();

    }
}
