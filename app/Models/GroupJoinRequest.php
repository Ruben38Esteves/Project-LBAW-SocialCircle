<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Group;

class GroupJoinRequest extends Model
{
    use HasFactory;
    protected $table = 'groupjoinrequest';
    public $timestamps = false;

    protected $fillable = [
        'groupid',
        'userid'
    ];
     public $primaryKey = ['groupid', 'userid'];

    public function group() {
        return $this->hasOne(Group::class);
    }

    public function user() {
        return $this->hasOne(User::class);
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
