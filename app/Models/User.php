<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facade\DB;

use App\Models\Post;
use App\Models\Event;
use App\Models\Comment;
use App\Models\Group;

use App\Policies\UserPolicy;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;
    protected $table = 'users';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'aboutme',
        'username',
        'email',
        'birthday',
        'nationality',
        'currentlocation',
        'password',
        'ispublic',
        'isadmin',
        'isactive'      
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Get all of the posts for the User
    public function ownPosts(){
        return $this->hasMany(Post::class, 'userid')->orderBy('created_at', 'desc');
    }

    public function ownEvents() {
        return $this->hasMany(Event::class, 'ownerid');
    }

    public function ownComments() {
        return $this->hasMany(Comment::class, 'creatorid');
    }

    public function isAdmin() {
        return $this->isadmin;
    }

    public function isPublic() {
        return $this->ispublic;
    }

    public function isActive(){
        return $this->isactive;
    }

    public function friends() {
        return $this->belongsToMany(User::class, 'friendship', 'userid', 'friendid');
    }

    public function groups() {
        return $this->belongsToMany(Group::class, 'groupmember', 'userid', 'groupid');
    }
    
    public function notifications(){
        return $this->hasManyThrough(UserNotification::class,Notification::class,'notifieduser','notificationid','id','notificationid');
    }
    
}
