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
 
class UserController extends Controller
{
    public function index(): View
    {
        
    }

    public function fillProfile($username){
        if(!Auth::check()){
            return redirect('/login');
        }else{ 
            $user = User::where('username', $username)->first();
            //$nonEventPosts = Post::where('userid', $user->userid)->get();
            return view('pages.profile', ['user' => $user, 'nonEventPosts' => $user->ownPosts]);
        }    
    }

}