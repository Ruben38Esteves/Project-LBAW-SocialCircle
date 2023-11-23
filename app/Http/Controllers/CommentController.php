<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index(): View
    {
        
    }

    public function create(Request $request, $postid){
        $user = Auth::user();
        $comment = new Comment;
        $comment->postid = $postid;
        $comment->creatorid = $user->id;
        $comment->content = $request->content;
        $comment->save();
        return redirect()->back();
    }
}