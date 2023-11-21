<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Comment;
use App\Models\Like;

class Post extends Model
{
    use HasFactory;
    
    protected $table = 'userpost';
    protected $primaryKey = 'postid';
    protected $connection = 'pgsql';
    #public $timestamps  = false;

    // preciso checkar se funçoes funcionam
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid');
    }
    /*
    public function comments():
    {
        return $this->hasMany(Comment::class);
    }

    public function likes():
    {
        return $this->hasMany(Like::class);
    }
*/

}
