<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'userPost';
    protected $primaryKey = 'postID';
    public function posts(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
