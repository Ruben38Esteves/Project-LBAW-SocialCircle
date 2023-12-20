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
use App\Models\GroupNotification;
use App\Models\UserNotification;
use App\Policies\UserPolicy;
use App\Models\Group;
use App\Models\Message;
use App\Models\Image;
 
class UserController extends Controller

{
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


    public function search(Request $request){
            $search = $request->get('query');

            $users = User::whereRaw("tsvectors @@ to_tsquery('english', ?)", [$search])
                ->orderByRaw("ts_rank(users.tsvectors, to_tsquery(?)) ASC", [$search])->get();

            $groups = Group::whereRaw("tsvectors @@ to_tsquery('english', ?)", [$search])
                ->orderByRaw("ts_rank(groups.tsvectors, to_tsquery(?)) ASC", [$search])->get();

            $posts = Post::whereRaw("tsvectors @@ to_tsquery('english', ?)", [$search])
                ->orderByRaw("ts_rank(userpost.tsvectors, to_tsquery(?)) ASC", [$search])->get();


            $postsResult = array();
            $usersResult = array();


            foreach ($posts as $post){
                if (!Auth::user()->can('view', $post)) {
                    continue;
                }

                $image = Image::where('imageid', User::where('id', $post->userid)->first()->profilepictureid)->first();
                if ($image){
                    $postUserImagePath = $image->imagepath;
                } else {
                    $postUserImagePath = 'default-user.jpg';
                }
                //$group = Group::where('groupid', $post->groupid)->first();
                $postUsername = User::where('id', $post->userid)->first()->username;
                $postUserID = User::where('id', $post->userid)->first()->userid;
                $postUserFirstName = User::where('id', $post->userid)->first()->firstname;
                $postUserLastName = User::where('id', $post->userid)->first()->lastname;                
                //$postGroupID = $post->groupid;
                //dd($group);
                //$postGroupName = $group->name;
                $postContent = $post->content;
                $postID = $post->postid;
                
                //dd($postGroupID);
                
                $postsResult[] = (object) [
                    'username' => $postUsername,
                    'userid' => $postUserID,
                    'imagepath' => $postUserImagePath,
                    'userfirstname' => $postUserFirstName,
                    'userlastname' => $postUserLastName,
                    //'groupid' => $postGroupID,
                    //'groupname' => $postGroupName,
                    'content' => $postContent,
                    'postid' => $postID
                ];
            }


            foreach ($users as $user) {
                $image = Image::where('imageid', $user->profilepictureid)->first();
                if ($image){
                    $imagepath = $image->imagepath;
                } else {
                    $imagepath = 'default-user.jpg';
                }
                $usersResult[] = (object) [
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'username' => $user->username,
                    'imagepath' => $imagepath
                ];
            }


            
            $results = (object) [
                'users' => $usersResult,
                'groups' => $groups,
                'posts' => $postsResult
            ];
            

            return $results;
    }

    /*
    public function search(Request $request)    {

        $search = $request->get('query');

        
        $users = User::whereRaw("tsvectors @@ to_tsquery('english', ?)", [$search])
        ->orderByRaw("ts_rank(users.tsvectors, to_tsquery(?)) ASC", [$search])->get();     
        
        //dd($users);
        return $users;
    }
    */
    public function fillProfile($username){
        $user = User::where('username', $username)->first();
        $notifications = $user->notifications;
        if(Auth::check()){
            $userself = Auth::user();
            if($userself->can('view', $user)){
                return view('pages.profile', ['user' => $user, 'nonEventPosts' => $user->ownPosts]);
            }else{
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


    public function homeFeed(){
        if(Auth::check()){
            $user = Auth::user();
            $posts = Post::all()->sortByDesc('created_at');
            foreach ($posts as $post) {
                if (!($user->can('view', $post))) {
                    $posts->forget($post);
                }
            }
            $notifications = $user->notifications;
            return view('pages.home', ['posts' => $posts]);
        }
    }
}    

