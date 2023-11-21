<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Friendship extends Model
{
    use HasFactory;

    protected $table = 'friendship';
    public $timestamps = false;

    protected $fillable = [
        'userID',
        'friendID'
    ];

    public function areFriends($userID, $friendID) {
        $friendship = Friendship::where('userID', $userID)->where('friendID', $friendID)->first();
        if ($friendship == null) {
            $friendship = Friendship::where('userID', $friendID)->where('friendID', $userID)->first();
            if ($friendship == null) {
                return false;
            }
        }
        return true;
    }
}
