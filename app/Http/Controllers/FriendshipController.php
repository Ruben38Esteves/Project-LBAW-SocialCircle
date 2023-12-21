<?php

namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\Event;
use App\Models\Friendship;
use App\Models\FriendRequest;
use App\Models\User;

class FriendshipController extends Controller
{
    public static function createRequest($friend) {
        $user = Auth::user();
        $userID = $user->id;
        $friend = User::where('username', $friend)->first();
        if($user->can('create', FriendRequest::class)){
            FriendRequest::create($userID, $friend->id);
        }
        return redirect()->route('user', ['username' => $friend->username]);
    }


    public static function removeRequest($friend) {
        $user = Auth::user();
        $userID = $user->id;
        $friend = User::where('username', $friend)->first();

        if($user->can('delete', FriendRequest::where([['sourceid', $userID],['targetid', $friend->id]])->first())){
            FriendRequest::where([
                ['sourceid', $userID],
                ['targetid', $friend->id]
            ])->delete();
        }

        return redirect('/profile/'.$friend);
    }


    public function acceptRequest($friend) {
        $user = Auth::user();
        $userID = $user->id;
        $friendAux = User::where('username', $friend)->first();

        $friendRequest = FriendRequest::where([
            ['sourceid', $friendAux->id],
            ['targetid', $userID]
        ])->first();

        if($user->can('create', Friendship::class)){
            $friendRequest->accept();
        }
        if($user->can('delete', FriendRequest::where([
            ['sourceid', $friendAux->id],
            ['targetid', $userID]
        ])->first())){
            FriendRequest::where([
                ['sourceid', $friendAux->id],
                ['targetid', $userID]
            ])->delete();
        }
        return redirect('/profile/'.$friend);
    }

    // erases friendship
    public function unfriend($friend) {
        $user = Auth::user();
        $userID = $user->id;
        $friendAux = User::where('username', $friend)->first();
    
        $friendship = Friendship::where(function ($query) use ($userID, $friendAux) {
            $query->where('userid', $userID)->where('friendid', $friendAux->id);
        })->orWhere(function ($query) use ($userID, $friendAux) {
            $query->where('userid', $friendAux->id)->where('friendid', $userID);
        })->first();
        if ($user->can('delete', $friendship)) {
            Friendship::where(function ($query) use ($userID, $friendAux) {
                $query->where('userid', $userID)->where('friendid', $friendAux->id);
            })->orWhere(function ($query) use ($userID, $friendAux) {
                $query->where('userid', $friendAux->id)->where('friendid', $userID);
            })->delete();
        }

        return redirect('/profile/'.$friend);
    }
}