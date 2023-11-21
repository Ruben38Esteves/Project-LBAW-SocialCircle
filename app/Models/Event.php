<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;
    
    protected $table = 'event';
    protected $primaryKey = 'eventid';
    protected $connection = 'pgsql';
    #public $timestamps  = false;

    protected $fillable = [
        'ownerid',
        'title',
        'pictureid',
        'description',
        'locationid',
        'startdate',
        'enddate',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ownerid');
    }
    public function posts() {
        return $this->hasMany(Post::class, 'eventid');
    }
}
