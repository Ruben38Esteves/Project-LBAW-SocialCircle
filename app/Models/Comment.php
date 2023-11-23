<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false; 
    protected $table = 'comment';
    protected $primaryKey = 'commentid';


    protected $fillable = [
        'commentid',
        'postid',
        'creatorid',
        'content',
        'created_at'
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'creatorid');
    }

    public function post() {
        return Post::find($this->postid);
    }
 

}
