<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\FriendRequest;

class Friendship extends Model
{
    use HasFactory;

    protected $table = 'friendship';
    public $timestamps = false;

    protected $fillable = [
        'userid',
        'friendid'
    ];

    public $primarykey = ['userid', 'friendid'];

    public static function areFriends($userID, $friendID) {
        $friendship = Friendship::where('userid', $userID)->where('friendid', $friendID)->first();
        if ($friendship == null) {
            $friendship = Friendship::where('userid', $friendID)->where('friendid', $userID)->first();
            if ($friendship == null) {
                return false;
            }
        }
        return true;
    }

    public static function create($userID, $friendID) {
        $friendship = new Friendship;
        $friendship->userid = $userID;
        $friendship->friendid = $friendID;
        $friendship->save();
    }

}
