<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';
    public $timestamps  = false;
    public $primaryKey = 'groupid';

    protected $fillable = [
        'name',
        'description',
        'ownerid',
        'ispublic',
        'isactive'
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'ownerid', 'id');
    }

    public function getMembers()
    {
        return $this->belongsToMany(User::class, 'groupmember', 'groupid', 'userid');
    }

    public function getPosts() {
        return $this->hasMany(Post::class, 'groupid', 'groupid')->orderBy('created_at', 'desc');
    }

    public function isMember(User $user) {
        return $this->getMembers()->where('userid', $user->id)->exists();
    }

    public function getJoinRequests() {
        return $this->hasMany(GroupJoinRequest::class, 'groupid', 'groupid');
    }

    public function addMember(User $user){
        $this->getMembers()->attach($user);
    }

    public function removeMember(User $user){
        $this->getMembers()->detach($user);
    }
}
