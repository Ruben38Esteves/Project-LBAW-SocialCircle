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

        FriendRequest::create($userID, $friend->id);
        return redirect()->route('user', ['username' => $friend->username]);
    }


    public static function removeRequest($friend) {
        $user = Auth::user();
        $userID = $user->id;
        $friend = User::where('username', $friend)->first();

        // $friendRequest = FriendRequest::where('sourceid', $userID)
        // ->where('targetid', $friend->id)->get();
        // dd($friendRequest);

        // $friendRequest->delete();

        
        FriendRequest::where([
                ['sourceid', $userID],
                ['targetid', $friend->id]
            ])->delete();

        // $friendRequest->delete();

        return view('pages.profile', ['user' => $friend]);
    }


    public function acceptRequest($friend) {
        $user = Auth::user();
        $userID = $user->id;
        $friendAux = User::where('username', $friend)->first();

        $friendRequest = FriendRequest::where([
            ['sourceid', $friendAux->id],
            ['targetid', $userID]
        ])->first();

        $friendRequest->accept();
        return view('pages.profile', ['user' => $friendAux]);
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
        });
    
        if ($friendship) {
            $friendship->delete();
        }
    
        return view('pages.profile', ['user' => $friendAux]);
    }
}