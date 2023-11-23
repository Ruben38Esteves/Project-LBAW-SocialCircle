<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Event;

class Post extends Model
{
    use HasFactory;
    
    protected $table = 'userpost';
    protected $primaryKey = 'postid';
    protected $connection = 'pgsql';
    public $timestamps = true;

    protected $fillable = [
        'userid',
        'content',
        'eventid'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function fromEvent(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'eventid');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'postid');
    }

}
