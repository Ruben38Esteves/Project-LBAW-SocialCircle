<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Friendship;

class FriendRequest extends Model
{
    use HasFactory;

    protected $table = 'friendrequest';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'sourceid',
        'targetid',
        'friendrequeststate'
    ];

    public $primaryKey = ['sourceid', 'targetid'];


    public static function create($sourceid, $targetid){
        $friendRequest = new FriendRequest;
        $friendRequest->sourceid = $sourceid;
        $friendRequest->targetid = $targetid;
        $friendRequest->friendrequeststate = 'pending';
        $friendRequest->save();
        //dd($friendRequest);
    }

    public function accept(){
        $this->friendrequeststate = 'accepted';
        FriendRequest::where('sourceid', $this->sourceid)
                    ->where('targetid', $this->targetid)
                    ->update(['friendrequeststate' => 'accepted']);
        
        FriendRequest::where('sourceid', $this->sourceid)
                    ->where('targetid', $this->targetid)
                    ->delete();
    }

    public static function exists($sourceid, $targetid){
        $friendRequest = FriendRequest::where('sourceid', $sourceid)->where('targetid', $targetid)->first();
        if($friendRequest == null){
            return false;
        }
        return true;
    }
}