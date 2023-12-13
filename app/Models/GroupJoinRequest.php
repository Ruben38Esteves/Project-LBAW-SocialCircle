<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Group;

class GroupJoinRequest extends Model
{
    use HasFactory;
    protected $table = 'groupjoinrequest';

    protected $fillable = [
        'groupid',
        'userid'
    ];

    protected $primaryKey = ['groupID', 'userID'];


    public function group() {
        return $this->hasOne(Group::class);
    }

    public function user() {
        dd($this);
        //return $this->hasOne(User::class, 'id', 'userid')
        if (property_exists($this, 'userid') && isset($this->userid)) {
            dd($this->userid);
        } else {
            dd("userid is not set or has an incorrect value");
        }
        return User::where('id', $this->userid)->first();
    }

    public static function exists($groupid, $userid){
        $groupjoinrequest = GroupJoinRequest::where('groupid', $groupid)->where('userid', $userid)->first();
        if($groupjoinrequest){
            return true;
        }else{
            return false;
        }
    }

}
