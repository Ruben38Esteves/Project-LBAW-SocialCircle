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
        return view('pages.profile', ['user' => $friend]);
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
        $friend = User::where('username', $friend)->first();

        $friendRequest = FriendRequest::where([
            ['sourceid', $userID],
            ['targetid', $friend->id]
        ])->first();

        $friendRequest->accept();
        DB::table('friendship')->insert([
            'userid' => $userID,
            'friendid' => $friend->id
        ]);
        DB::table('friendship')->insert([
            'userid' => $friend->id,
            'friendid' => $userID
        ]);
        
        return view('pages.profile', ['user' => $friend]);
    }
}