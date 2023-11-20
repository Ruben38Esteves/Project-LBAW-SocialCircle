<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;

class Comment extends Model
{
    use HasFactory;

    public $timestamps  = false;
    protected $table = 'comment';
    protected $primaryKey = 'commentID';


    protected $fillable = [
        'commentID',
        'postID',
        'creatorID',
        'comment',
        'content',
    ];

    public function owner() {
        return User::find($this->creatorID);
    }

    public function post() {
        return Post::find($this->postID);
    }
 

}
