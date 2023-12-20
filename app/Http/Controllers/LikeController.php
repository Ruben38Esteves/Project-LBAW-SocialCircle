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
        $like = new Like();
        $like->postid = $postid;
        $like->userid = Auth::id();
        $like->save();
        return redirect()->back();
    }

    public function dislike($postid)
    {
        $like = Like::where('postid',$postid)->first();
        $like->delete();
        return redirect()->back();

    }
}
