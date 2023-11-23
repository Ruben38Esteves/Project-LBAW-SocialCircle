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


    public function fillProfile($username){
        $user = User::where('username', $username)->first();
        if($user->ispublic){
            return view('pages.profile', ['user' => $user, 'nonEventPosts' => $user->ownPosts]);
        }elseif (Auth::check()) {
            $user_me = Auth::user();
            if($user_me->isFriend($user->userid)){
                return view('pages.profile', ['user' => $user, 'nonEventPosts' => $user->ownPosts]);
            }
        }else{
            return redirect('/login');
        }
    }
}    