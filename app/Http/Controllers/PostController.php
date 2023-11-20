<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\Post;
 
class PostController extends Controller
{
    public function index(): View
    {
        
    }

    public function list(){
        if(!Auth::check()){
            return redirect('/login');
        }else{
            
            #$posts = DB::table('userPost')->get();
            #$posts = Post::all();
            $posts = DB::select('select * from "userPost"');
            return view('pages.home', ['posts' => $posts]);
        }

    }
}