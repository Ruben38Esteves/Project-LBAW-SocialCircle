<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupNotification extends Model
{
    use HasFactory;

    protected $table = 'groupnotification';
    public $timestamps = false;

    protected $fillable = [
        'groupid',
        'notificationid',
        'notification_type'
    ];
}
