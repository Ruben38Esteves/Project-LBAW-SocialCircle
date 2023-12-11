<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;

class UserNotification extends Model{
    use HasFactory;

    protected $table = 'usernotification';
    public $timestamps = false;

    protected $fillable = [
        'notificationid',
        'userid',
        'notification_type',
        'viewed',
        'created_at'
    ];

    public function notification(){
        return $this->belongsTo(Notification::class, 'notificationid');
    }

    public function fromUser(){
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function user(){
        $notif=Notification::where('notificationid',$this->notificationid);
        $user=User::where('id',$notif->user_id);
        return $user;
    }

    public function text(){
        $text = $this->fromUser()->get()->first()->firstname .' ' . $this->fromUser()->get()->first()->lastname . ' has ';
        switch( $this->notification_type ){
            case 'accepted_friend_request':
                $text .= 'accepted your friend request.';
                break;
            case 'request_friendship':
                $text .= 'sent a friend request.';
                break;
            case 'received_message':
                $text .= 'sent you a message';
                break;
        }
        return $text;
    }
}