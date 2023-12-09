<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\Post;
use App\Models\User;
use App\Models\Group;
use App\Models\Message;
 
class UserController extends Controller
{
    public function index(): View
    {
        
    }

    

    public function isFriend($id){
        $user = Auth::user();
        $friends = $user->friends;
        foreach ($friends as $friend) {
            if ($friend->userid == $id) {
                return true;
            }
        }
        return false;
    }

    public function search(Request $request)    {

        $search = $request->get('query');

        
        $users = User::whereRaw("tsvectors @@ to_tsquery('english', ?)", [$search])
        ->orderByRaw("ts_rank(users.tsvectors, to_tsquery(?)) ASC", [$search])->get();     
        
        //dd($users);
        return $users;
    }

    public function fillProfile($username){
        $user = User::where('username', $username)->first();
        if(Auth::check()){
            if(Auth::user()->cannot('view', $user)){
                return redirect('/login');
            }
        }elseif(!($user->ispublic)){
            return redirect('/login');
        }else{
            return view('pages.profile', ['user' => $user, 'nonEventPosts' => $user->ownPosts]);
        }
    }

    public function friends($username){
        $user = User::where('username', $username)->first();
        if($user->ispublic){
            return $user->friends;
        }elseif (Auth::check()) {
            $user_me = Auth::user();
            if($user_me->isFriend($user->userid)){
                return $user->friends;
            }
        }else{
            return redirect('/login');
        }
    }

    public function groups($username){
        $user = User::where('username', $username)->first();
        $user_me = Auth::user();
        if($user==$user_me){
            return $user->groups;
        }
        if($user->ispublic){
            return $user->groups;
        }elseif (Auth::check()) {
            if($user_me->isFriend($user->userid)){
                return $user->groups;
            }
        }else{
            return redirect('/login');
        }
    }
}    

