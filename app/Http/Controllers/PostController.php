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
 
class PostController extends Controller{
    
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


    public function homeFeed(){
        if(!Auth::check()){
            $posts = Post::all()->sortByCresc('created_at');
            return view('pages.home', ['posts' => $posts]);
        }else{
            $user = Auth::user();
            $friends = $user->friends;
            $posts = $user->ownPosts;
            foreach ($friends as $friend) {
                if ($friend->ownPosts != null) {
                    $posts = $posts->concat($friend->ownPosts);
                }
            }
            $posts = $posts->sortByDesc('created_at');
            return view('pages.home', ['posts' => $posts]);
        }
    }

    public function create(Request $request){
        if(!Auth::check()){
            return redirect('/login');
        }else{
            $user = Auth::user();
            $post = new Post();
            $post->userid = $user->id;
            $post->content = $request->content;
            $post->save();
            return redirect('/home');
        }
    }


    public function search(Request $request)    {
        if(!Auth::check())
            return redirect('/login');

        $search = $request->get('query');

        
        $posts = Post::whereRaw("tsvectors @@ to_tsquery('english', ?)", [$search])
        ->orderByRaw("ts_rank(users.tsvectors, to_tsquery(?)) ASC", [$search])->get();     
        
        //dd(Post::paginate(10));
        return $posts;

    public function edit(Request $request, $id){
        if(!Auth::check()){
            return redirect('/login');
        }else{
            $user = Auth::user();
            $post = Post::where('postid', $id)->first();
            if($post->userid == $user->id){
                $post->content = $request->content;
                $post->save();
            }
            return redirect('/home');
        }
    }

    public function delete($id){
        if(!Auth::check()){
            return redirect('/login');
        }else{
            $user = Auth::user();
            $post = Post::where('postid', $id)->first();
            if($post->userid == $user->id){
                $post->delete();
            }
            return redirect('/home');
        }
    }

}

