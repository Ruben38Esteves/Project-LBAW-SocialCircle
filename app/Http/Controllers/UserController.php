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

    public function getUser($username){
        if(!Auth::check()){
            return redirect('/login');
        }else{ 
            $user = User::where('username', $username)->first();
            return view('pages.profile', ['user' => $user]);
        }    
    }

    public function search(Request $request)    {
        if(!Auth::check())
            return redirect('/login');

        $search = $request->get('query');

        
        $users = User::whereRaw("tsvectors @@ to_tsquery('english', ?)", [$search])
        ->orderByRaw("ts_rank(users.tsvectors, to_tsquery(?)) ASC", [$search])->get();     
        
        //dd(Post::paginate(10));
        return $users;
    }
}