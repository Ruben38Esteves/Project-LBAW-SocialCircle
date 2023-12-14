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
    public $incrementing = false;
    protected $primaryKey = ['groupid', 'userid'];


    public function group() {
        $group = Group::where('id', $this->groupid)->first();
        return $group;
    }

    public function user() {
        $user = User::where('id', $this->userid)->first();
        return $user;
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
