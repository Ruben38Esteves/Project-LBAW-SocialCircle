<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Post;

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
        'password',
        'ispublic',
        'isAdmin'      
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
        return $this->isAdmin;
    }

    public function friends() {
        return $this->belongsToMany(User::class, 'friendship', 'userid', 'friendid');
    }

}
