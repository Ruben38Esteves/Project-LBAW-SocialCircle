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
 
class PostController extends Controller
{
    public function index(): View
    {
        
    }

    public function list(){
        if(!Auth::check()){
            return redirect('/login');
        }else{
            $posts = Post::all();
            return view('pages.home', ['posts' => $posts]);
        }

    }

    public function getNonEventPosts($username){
        if(!Auth::check()){
            return redirect('/login');
        }else{
            $user = User::where('username', $username)->first();
            $nonEventPosts = Post::where('userid', $user->userid)->where('eventid', null)->get();
            return view('pages.profile', ['nonEventPosts' => $nonEventPosts]);
        }
    }
}