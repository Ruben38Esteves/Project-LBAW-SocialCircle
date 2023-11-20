<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupNotification extends Model
{
    use HasFactory;

    protected $table = 'groupNotification';
    public $timestamps = false;

    protected $fillable = [
        'groupID',
        'notificationID'
    ];
}
