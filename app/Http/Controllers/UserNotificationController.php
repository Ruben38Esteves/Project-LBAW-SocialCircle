<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\UserNotification;
use App\Policies\UserNotificationPolicy;

class UserNotificationController extends Controller
{
    public function markAsViewed(Request $request, $id){
        if(Auth::check()){
            $user = Auth::user();
            $notif = UserNotification::where('notificationid',$id)->first();
            if($user->id == $notif->userid()){
                $affected = DB::table('usernotification')
                    ->where('notificationid', $id)
                    ->update(['viewed' => true]);
                return redirect('/home');
            }
        }
        return redirect('/home');
    }

    public function markNotifAsViewed($id){
        $affected = DB::table('usernotification')
            ->where('notificationid', $id)
            ->update(['viewed' => true]);
        return redirect('/home');
    }
}
