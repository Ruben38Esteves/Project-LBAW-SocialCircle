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

    public Function owner() {
        return User::find($this->creatorID);
    }

    public function getMembers()
    {
        return $this->belongsToMany('App\Models\User', 'groupmember', 'groupid', 'userid');
    }

    public function getPosts() {
        return $this->hasMany(Post::class, 'groupid', 'groupid')->orderBy('created_at', 'desc');
    }

    public function isMember(User $user) {
        return $this->getMembers()->where('userid', $user->id)->exists();
    }
}
