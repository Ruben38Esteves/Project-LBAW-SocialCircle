<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\Post;
 
class PostController extends Controller{
    
    public function index(): View
    {
        
    }


    public function list(){
        if(!Auth::check()){
            return redirect('/login');
        }else{
            
            $posts = Post::all();
            #$posts = DB::select('select * from userPost');
            return view('pages.home', ['posts' => $posts]);
        }
    }

    public function homeFeed(){
        if(!Auth::check()){
            return redirect('/login');
        }else{
            $user = Auth::user();
            $friends = $user->friends;
            $posts = $user->ownPosts;
            foreach ($friends as $friend) {
                if ($friend->ownPosts != null) {
                    $posts = $posts->concat($friend->ownPosts);
                }
            }
            return view('pages.home', ['posts' => $posts]);
        }
    }
}
